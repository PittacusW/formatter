<?php

namespace App\Models\Admin;

use App\Utils\Sii;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class Business extends Model {
	use Notifiable;

	public static $snakeAttributes = false;

	public $timestamps = false;

	protected $connection = 'general';

	protected $table = 'empresas';

	protected $primaryKey = 'idEmpresas';

	protected $configurable = ['razonSocial', 'direccion', 'idComunas', 'telefono', 'email', 'idActividadEconomica', 'idTipoSociedad', 'idRegimenTributario', 'giro', 'idMutuales', 'idCcaf', 'sii'];

	protected $fillable = ['idTipoSociedad', 'idActividadEconomica', 'idRegimenTributario', 'idComunas', 'idCcaf', 'idMutuales', 'alias', 'rut', 'razonSocial', 'direccion', 'telefono', 'email', 'paginaWeb', 'giro', 'fechaResolucion', 'nroResolucion', 'siiPassword', 'colores', 'activo'];

	protected $appends = ['id', 'root', 'representatives', 'logo', 'configured', 'idPlans', 'idCertifications'];

	protected $hidden = ['idSucursales', 'idBancos', 'idTipoCuenta', 'cuenta', 'siiPassword', 'plans', 'certifications'];

	protected $dates = ['fechaResolucion'];

	protected $with = ['plans', 'certifications'];

	protected $casts = ['activo' => 'int'];

	public function plans() {
		return $this->belongsToMany(Plan::
				class, 'modulos_empresas', 'idEmpresas', 'idModulosPlanes');
	}

	public function certifications() {
		return $this->belongsToMany(Certification::
				class, 'certificaciones_empresas', 'idEmpresas', 'idModulosCertificaciones')->withPivot('trackId', 'status');
	}

	protected static function boot() {
		parent::boot();
		static::creating(function ($model) {
			$sii = new Sii($model->rut);
			$model->razonSocial = $sii->getRazonSocial();
			$model->idActividadEconomica = $sii->getActividadEconomica();
		});
	}

	public function upload($file, $default = false) {

		if ($default && (is_null($file) || !$file->isValid())) {
			$file = new UploadedFile(public_path('assets/img/empty-logo.png'), 'empty-logo.png', 'image/png');
		}
		$img = $file->storeAs('logos', "{$this->getKey()}.png");
		$path = storage_path("app/public/{$img}");
		Image::make($path)->resize(null, 600, function ($constraint) {
			$constraint->aspectRatio();
		})->save($path, 100);
	}

	public function eliminate() {
		Storage::delete("logos/{$this->getKey()}.png");
		$this->delete();
	}

	public function syncPlans(array $plans) {
		$this->plans()->sync($plans, true);
		$this->load('plans');
	}

	public function syncCertifications(array $certifications, $detach = true) {
		$this->certifications()->sync($certifications, $detach);
		$this->load('certifications');
	}

	public function getConfigurable() {
		return $this->configurable;
	}

	public function isConfigured() {
		foreach ($this->getConfigurable() as $attribute) {

			if (empty($this->getAttribute($attribute))) {
				return false;
			}
		}
		return true;
	}

	public function hasCommercialManagementModule() {
		return $this->plans()->where('idModulos', 1)->exists();
	}

	public function getDatabaseName() {
		return app()->environment('production') ? "erp_{$this->alias}" : env('DB_DATABASE2');
	}

	public function setDatabaseName() {
		$name = $this->getDatabaseName();

		if (!Database::exists($name)) {
			return false;
		}
		Database::setConnectionDatabaseName('particular', $name);
		return true;
	}

	public function getIdAttribute() {
		return $this->getKey();
	}

	public function setRutAttribute($value) {
		$this->attributes['rut'] = filter_rut($value);
	}

	public function setAliasAttribute($value) {
		$this->attributes['alias'] = strtolower($value);
	}

	public function setFechaResolucionAttribute($value) {
		$this->attributes['fechaResolucion'] = empty($value) ? null : Carbon::parse($value)->format('Y-m-d');
	}

	public function getFechaResolucionAttribute($value) {
		return empty($value) ? null : Carbon::parse($value)->format('d-m-Y');
	}

	public function setSiiPasswordAttribute($value) {

		if (!empty($value)) {
			$this->attributes['siiPassword'] = Object::newInstance('Auth', ['dbp', 'dbg'])->fill($value)->exec('generatorPassword');
		}
	}

	public function getSiiPasswordAttribute($value) {
		try {
			return empty($value) ? null : Object::newInstance('Auth', ['dbp', 'dbg'])->fill($value)->exec('unlock');
		} catch (\Exception $e) {
			return null;
		}
	}

	public function setColoresAttribute($value) {
		$this->attributes['colores'] = json_encode($value);
	}

	public function getColoresAttribute() {
		return json_decode($this->attributes['colores']);
	}

	public function getRootAttribute() {

		if (!$this->setDatabaseName()) {
			return null;
		}
		return NaturalPerson::select('rut', 'email')->isAdmin()->first();
	}

	public function getRepresentativesAttribute() {

		if (!$this->setDatabaseName()) {
			return null;
		}
		return NaturalPerson::isRepresentative()->get()->pluck('rut');
	}

	public function getLogoAttribute() {
		$path = "logos/{$this->getKey()}.png";

		if (!Storage::exists($path)) {
			return null;
		}
		return Storage::url($path) . "?_dc=" . Storage::lastModified($path);
	}

	public function getConfiguredAttribute() {
		return Storage::disk('private')->exists("terms/{$this->getKey()}.pdf");
	}

	public function getIdPlansAttribute() {
		return $this->relationLoaded('plans') ? $this->plans->pluck(with(new Plan)->getKeyName()) : [];
	}

	public function getIdCertificationsAttribute() {
		return $this->relationLoaded('certifications') ? $this->certifications->pluck(with(new Certification)->getKeyName()) : [];
	}
}
