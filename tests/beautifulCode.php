staticfunction<?php

namespace App\Models\Admin;

use Illuminate\Support\Facades\Storage;
use App\Models\Admin\ForeignKeys;
use App\Models\Admin\Conexiones;
use App\Models\Admin\Modelos;
use App\Models\Admin\Controladores;
use Contal\Formatter;

class Crafter {

	public 
	static function modelos() {
		return Table::all()->transform(function ($table) {
			$id = Modelos::where('tabla', $table)->pluck('id')->first();
			$name = studly_case($table->get('name'));
			$table = snake_case($name);
			$conexion = config('database.default');
			$columns = Column::all($table);
			return collect(['id' => empty($id) ? null : $id, 'name' => $name, 'table' => $table, 'conexion' => $conexion, 'fillable' => $columns->where('primary', false)->pluck('name') , 'atributos' => empty($id) ? [] : Modelos::find($id)->atributos, 'funciones' => empty($id) ? [] : Modelos::find($id)->funciones, 'exists' => empty($id) ? false : true]);
		});
	}
	public 
	static function controladores() {
		return Table::all()->transform(function ($table) {
			$id = Controladores::where('tabla', $table)->pluck('id')->first();
			$name = studly_case($table->get('name'));
			$table = snake_case($name);
			$conexion = config('database.default');
			return collect(['id' => empty($id) ? null : $id, 'name' => $name, 'table' => $table, 'conexion' => $conexion, 'exists' => empty($id) ? false : true, 'atributos' => empty($id) ? [] : Controladores::find($id)->atributos, 'funciones' => empty($id) ? [] : Controladores::find($id)->funciones, 'rutas' => empty($id) ? [] : Controladores::find($id)->rutas, ]);
		});
	}
	public 
	static function findModelo($id) {
		return self::modelos()->where('id', $id)->first();
	}
	public 
	static function findControlador($id) {
		return self::controladores()->where('id', $id)->first();
	}
	public 
	static function craftModel($table) {
		$connection = config('database.default');
		$idConexion = Conexiones::where('nombre', $connection)->pluck('id')->first();
		$namespace = ucfirst($connection);
		$name = studly_case($table);
		$columns = Column::all($table);
		$fillable = $columns->where('primary', false)->pluck('name');
		$has = ForeignKeys::has($table);
		$belongs = ForeignKeys::belongs($table);
		$casts = array_pluck($belongs, 'referenced_column');
		$has = $has->transform(function ($data) {
			$clase = studly_case($data['referenced_table']);
			$funcion = camel_case($data['referenced_table']);
			$conexion = studly_case($data['referenced_connection']);
			return collect(['function' => $funcion, 'connection' => $conexion, 'class' => $clase, 'column' => $data['referenced_column']]);
		});
		$belongs = $belongs->transform(function ($data) {
			$clase = studly_case($data['origin_table']);
			$funcion = camel_case($data['origin_table']);
			$conexion = studly_case($data['origin_connection']);
			return collect(['function' => $funcion, 'connection' => $conexion, 'class' => $clase, 'column' => $data['origin_column']]);
		});
		$id = Modelos::where('tabla', $table)->pluck('id')->first();
		
		if (empty($id)) {
			Modelos::create(['tabla' => $table, 'idConexiones' => $idConexion]);
			$uses = [];
			$traits = [];
			$hiddens = [];
			$configurables = [];
			$appends = [];
			$withs = [];
			$functions = [];
			$scopes = [];
			$relaciones = [];
			$accessors = [];
			$mutators = [];
		}
		else {
			$uses = Modelos::find($id)->atributos()->where('tipo_atributo_id', 1)->pluck('contenido');
			$traits = Modelos::find($id)->atributos()->where('tipo_atributo_id', 2)->pluck('contenido');
			$hiddens = Modelos::find($id)->atributos()->where('tipo_atributo_id', 3)->pluck('contenido');
			$configurables = Modelos::find($id)->atributos()->where('tipo_atributo_id', 4)->pluck('contenido');
			$appends = Modelos::find($id)->atributos()->where('tipo_atributo_id', 5)->pluck('contenido');
			$withs = Modelos::find($id)->atributos()->where('tipo_atributo_id', 6)->pluck('contenido');
			$functions = Modelos::find($id)->funciones()->where('metodos_id', 1);
			$scopes = Modelos::find($id)->funciones()->where('metodos_id', 2);
			$relaciones = Modelos::find($id)->funciones()->where('metodos_id', 3);
			$accessors = Modelos::find($id)->funciones()->where('metodos_id', 4);
			$mutators = Modelos::find($id)->funciones()->where('metodos_id', 5);
		}
		$content = view('layouts.models', compact('namespace', 'name', 'connection', 'table', 'fillable', 'casts', 'has', 'belongs', 'uses', 'traits', 'hiddens', 'configurables', 'appends', 'withs', 'functions', 'scopes', 'relaciones', 'accessors', 'mutators'))->render();
		$content = Formatter::format("<?php\r\n\r\n$content");
		Storage::disk("models-{$connection}")->put("{$name}.php", $content);
		return self::findModelo($id);
	}
	public 
	static function update($id, $data) {
		self::find($id)->update($data);
		return self::generate($data['table']);
	}
	public 
	static function delete($id) {
		$name = camel_case($id);
		Storage::disk('objects')->delete("$name.php");
		return compact('name');
	}
}
