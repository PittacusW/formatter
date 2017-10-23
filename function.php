<?php

include_once 'src/Pear/PEAR.php';

include_once 'src/Log.php';

include_once 'src/Pear/Exception.php';

include_once 'src/Beautifier/Batch/Output.php';

include_once 'src/Beautifier/Batch/Files.php';

include_once 'src/Beautifier/BeautifierInterface.php';

include_once 'src/Beautifier/Decorator.php';

include_once 'src/Beautifier/Filter.php';

include_once 'src/Beautifier/Batch.php';

include_once 'src/Beautifier/Common.php';

include_once 'src/Beautifier/Exception.php';

include_once 'src/Beautifier/Filter/ArrayNested.filter.php';

include_once 'src/Beautifier/Filter/Default.filter.php';

include_once 'src/Beautifier/Filter/DocBlock.filter.php';

include_once 'src/Beautifier/Filter/EqualsAlign.filter.php';

include_once 'src/Beautifier/Filter/IndentStyles.filter.php';

include_once 'src/Beautifier/Filter/ListClassFunction.filter.php';

include_once 'src/Beautifier/Filter/Lowercase.filter.php';

include_once 'src/Beautifier/Filter/NewLines.filter.php';

include_once 'src/Beautifier/Filter/Pear.filter.php';

include_once 'src/Beautifier/Filter/phpBB.filter.php';

include_once 'src/Beautifier/StreamWrapper.php';

include_once 'src/Beautifier/Tokenizer.php';

include_once 'src/Beautifier/StreamWrapper/Tar.php';

include_once 'src/Beautifier/StreamWrapper/Tarz.php';

include_once 'src/Beautifier.php';

include_once 'src/CompatInfo.php';

include_once 'src/CompatInfo/apache2handler_sapi_array.php';

include_once 'src/CompatInfo/bcmath_func_array.php';

include_once 'src/CompatInfo/bz2_func_array.php';

include_once 'src/CompatInfo/calendar_const_array.php';

include_once 'src/CompatInfo/calendar_func_array.php';

include_once 'src/CompatInfo/cgi_sapi_array.php';

include_once 'src/CompatInfo/Cli.php';

include_once 'src/CompatInfo/cli_sapi_array.php';

include_once 'src/CompatInfo/com_dotnet_class_array.php';

include_once 'src/CompatInfo/com_dotnet_const_array.php';

include_once 'src/CompatInfo/com_dotnet_func_array.php';

include_once 'src/CompatInfo/ctype_func_array.php';

include_once 'src/CompatInfo/date_class_array.php';

include_once 'src/CompatInfo/date_const_array.php';

include_once 'src/CompatInfo/date_func_array.php';

include_once 'src/CompatInfo/dom_class_array.php';

include_once 'src/CompatInfo/dom_const_array.php';

include_once 'src/CompatInfo/dom_func_array.php';

include_once 'src/CompatInfo/filter_const_array.php';

include_once 'src/CompatInfo/filter_func_array.php';

include_once 'src/CompatInfo/ftp_const_array.php';

include_once 'src/CompatInfo/ftp_func_array.php';

include_once 'src/CompatInfo/gd_const_array.php';

include_once 'src/CompatInfo/gd_func_array.php';

include_once 'src/CompatInfo/gettext_func_array.php';

include_once 'src/CompatInfo/gmp_const_array.php';

include_once 'src/CompatInfo/gmp_func_array.php';

include_once 'src/CompatInfo/hash_const_array.php';

include_once 'src/CompatInfo/hash_func_array.php';

include_once 'src/CompatInfo/iconv_const_array.php';

include_once 'src/CompatInfo/iconv_func_array.php';

include_once 'src/CompatInfo/imap_const_array.php';

include_once 'src/CompatInfo/imap_func_array.php';

include_once 'src/CompatInfo/internal_const_array.php';

include_once 'src/CompatInfo/json_func_array.php';

include_once 'src/CompatInfo/libxml_class_array.php';

include_once 'src/CompatInfo/libxml_const_array.php';

include_once 'src/CompatInfo/libxml_func_array.php';

include_once 'src/CompatInfo/mbstring_const_array.php';

include_once 'src/CompatInfo/mbstring_func_array.php';

include_once 'src/CompatInfo/ming_class_array.php';

include_once 'src/CompatInfo/ming_const_array.php';

include_once 'src/CompatInfo/ming_func_array.php';

include_once 'src/CompatInfo/mysqli_class_array.php';

include_once 'src/CompatInfo/mysqli_const_array.php';

include_once 'src/CompatInfo/mysqli_func_array.php';

include_once 'src/CompatInfo/mysql_const_array.php';

include_once 'src/CompatInfo/mysql_func_array.php';

include_once 'src/CompatInfo/odbc_const_array.php';

include_once 'src/CompatInfo/odbc_func_array.php';

include_once 'src/CompatInfo/openssl_const_array.php';

include_once 'src/CompatInfo/openssl_func_array.php';

include_once 'src/CompatInfo/Parser.php';

include_once 'src/CompatInfo/pcre_const_array.php';

include_once 'src/CompatInfo/pcre_func_array.php';

include_once 'src/CompatInfo/PDO_class_array.php';

include_once 'src/CompatInfo/PDO_func_array.php';

include_once 'src/CompatInfo/pgsql_const_array.php';

include_once 'src/CompatInfo/pgsql_func_array.php';

include_once 'src/CompatInfo/Reflection_class_array.php';

include_once 'src/CompatInfo/Renderer.php';

include_once 'src/CompatInfo/Renderer/Array.php';

include_once 'src/CompatInfo/Renderer/Csv.php';

include_once 'src/CompatInfo/Renderer/Html.php';

include_once 'src/CompatInfo/Renderer/Null.php';

include_once 'src/CompatInfo/Renderer/Text.php';

include_once 'src/CompatInfo/Renderer/Xml.php';

include_once 'src/CompatInfo/sapi_array.php';

include_once 'src/CompatInfo/session_func_array.php';

include_once 'src/CompatInfo/SimpleXML_class_array.php';

include_once 'src/CompatInfo/SimpleXML_func_array.php';

include_once 'src/CompatInfo/snmp_const_array.php';

include_once 'src/CompatInfo/snmp_func_array.php';

include_once 'src/CompatInfo/SPL_class_array.php';

include_once 'src/CompatInfo/SPL_func_array.php';

include_once 'src/CompatInfo/SQLite_class_array.php';

include_once 'src/CompatInfo/SQLite_const_array.php';

include_once 'src/CompatInfo/SQLite_func_array.php';

include_once 'src/CompatInfo/standard_class_array.php';

include_once 'src/CompatInfo/standard_const_array.php';

include_once 'src/CompatInfo/standard_func_array.php';

include_once 'src/CompatInfo/tokenizer_const_array.php';

include_once 'src/CompatInfo/tokenizer_func_array.php';

include_once 'src/CompatInfo/wddx_func_array.php';

include_once 'src/CompatInfo/xdebug_const_array.php';

include_once 'src/CompatInfo/xdebug_func_array.php';

include_once 'src/CompatInfo/xmlreader_class_array.php';

include_once 'src/CompatInfo/xmlwriter_class_array.php';

include_once 'src/CompatInfo/xmlwriter_func_array.php';

include_once 'src/CompatInfo/xml_const_array.php';

include_once 'src/CompatInfo/xml_func_array.php';

include_once 'src/CompatInfo/xsl_class_array.php';

include_once 'src/CompatInfo/xsl_const_array.php';

include_once 'src/CompatInfo/zip_class_array.php';

include_once 'src/CompatInfo/zip_func_array.php';

include_once 'src/CompatInfo/zlib_const_array.php';

include_once 'src/CompatInfo/zlib_func_array.php';

include_once 'src/CompatInfo/class_array.php';

include_once 'src/CompatInfo/const_array.php';

include_once 'src/CompatInfo/func_array.php';

include_once 'src/Console/Getargs.php';

include_once 'src/Console/Getopt.php';

include_once 'src/Console/Table.php';

include_once 'src/DocBlockGenerator/Align.php';

include_once 'src/DocBlockGenerator/Block.php';

include_once 'src/DocBlockGenerator/Cli.php';

include_once 'src/DocBlockGenerator/GetoptPlus.php';

include_once 'src/DocBlockGenerator/License.php';

include_once 'src/DocBlockGenerator/Tokens.php';

include_once 'src/DocBlockGenerator/Type.php';

include_once 'src/DocBlockGenerator.php';

include_once 'src/Fmt/Additionals/AdditionalPass.php';

include_once 'src/Fmt/Additionals/AddMissingParentheses.php';

include_once 'src/Fmt/Additionals/AliasToMaster.php';

include_once 'src/Fmt/Additionals/AlignConstVisibilityEquals.php';

include_once 'src/Fmt/Additionals/AlignDoubleArrow.php';

include_once 'src/Fmt/Additionals/AlignDoubleSlashComments.php';

include_once 'src/Fmt/Additionals/AlignEquals.php';

include_once 'src/Fmt/Additionals/AlignGroupDoubleArrow.php';

include_once 'src/Fmt/Additionals/AlignPHPCode.php';

include_once 'src/Fmt/Additionals/AlignTypehint.php';

include_once 'src/Fmt/Additionals/AllmanStyleBraces.php';

include_once 'src/Fmt/Additionals/AutoPreincrement.php';

include_once 'src/Fmt/Additionals/AutoSemicolon.php';

include_once 'src/Fmt/Additionals/CakePHPStyle.php';

include_once 'src/Fmt/Additionals/ClassToSelf.php';

include_once 'src/Fmt/Additionals/ClassToStatic.php';

include_once 'src/Fmt/Additionals/ConvertOpenTagWithEcho.php';

include_once 'src/Fmt/Additionals/DocBlockToComment.php';

include_once 'src/Fmt/Additionals/DoubleToSingleQuote.php';

include_once 'src/Fmt/Additionals/EchoToPrint.php';

include_once 'src/Fmt/Additionals/EncapsulateNamespaces.php';

include_once 'src/Fmt/Additionals/GeneratePHPDoc.php';

include_once 'src/Fmt/Additionals/IndentTernaryConditions.php';

include_once 'src/Fmt/Additionals/JoinToImplode.php';

include_once 'src/Fmt/Additionals/LeftWordWrap.php';

include_once 'src/Fmt/Additionals/LongArray.php';

include_once 'src/Fmt/Additionals/MergeElseIf.php';

include_once 'src/Fmt/Additionals/MergeNamespaceWithOpenTag.php';

include_once 'src/Fmt/Additionals/MildAutoPreincrement.php';

include_once 'src/Fmt/Additionals/NewLineBeforeReturn.php';

include_once 'src/Fmt/Additionals/NoSpaceAfterPHPDocBlocks.php';

include_once 'src/Fmt/Additionals/OnlyOrderUseClauses.php';

include_once 'src/Fmt/Additionals/OrderAndRemoveUseClauses.php';

include_once 'src/Fmt/Additionals/OrderMethod.php';

include_once 'src/Fmt/Additionals/OrderMethodAndVisibility.php';

include_once 'src/Fmt/Additionals/OrganizeClass.php';

include_once 'src/Fmt/Additionals/PHPDocTypesToFunctionTypehint.php';

include_once 'src/Fmt/Additionals/PrettyPrintDocBlocks.php';

include_once 'src/Fmt/Additionals/PSR2EmptyFunction.php';

include_once 'src/Fmt/Additionals/PSR2MultilineFunctionParams.php';

include_once 'src/Fmt/Additionals/ReindentAndAlignObjOps.php';

include_once 'src/Fmt/Additionals/ReindentSwitchBlocks.php';

include_once 'src/Fmt/Additionals/RemoveIncludeParentheses.php';

include_once 'src/Fmt/Additionals/RemoveSemicolonAfterCurly.php';

include_once 'src/Fmt/Additionals/RemoveUseLeadingSlash.php';

include_once 'src/Fmt/Additionals/ReplaceBooleanAndOr.php';

include_once 'src/Fmt/Additionals/ReplaceIsNull.php';

include_once 'src/Fmt/Additionals/RestoreComments.php';

include_once 'src/Fmt/Additionals/ReturnNull.php';

include_once 'src/Fmt/Additionals/ShortArray.php';

include_once 'src/Fmt/Additionals/SmartLnAfterCurlyOpen.php';

include_once 'src/Fmt/Additionals/SortUseNameSpace.php';

include_once 'src/Fmt/Additionals/SpaceAroundControlStructures.php';

include_once 'src/Fmt/Additionals/SpaceAroundExclamationMark.php';

include_once 'src/Fmt/Additionals/SpaceBetweenMethods.php';

include_once 'src/Fmt/Additionals/SplitElseIf.php';

include_once 'src/Fmt/Additionals/StrictBehavior.php';

include_once 'src/Fmt/Additionals/StrictComparison.php';

include_once 'src/Fmt/Additionals/StripExtraCommaInArray.php';

include_once 'src/Fmt/Additionals/StripNewlineAfterClassOpen.php';

include_once 'src/Fmt/Additionals/StripNewlineAfterCurlyOpen.php';

include_once 'src/Fmt/Additionals/StripNewlineWithinClassBody.php';

include_once 'src/Fmt/Additionals/StripSpaces.php';

include_once 'src/Fmt/Additionals/StripSpaceWithinControlStructures.php';

include_once 'src/Fmt/Additionals/TightConcat.php';

include_once 'src/Fmt/Additionals/TrimSpaceBeforeSemicolon.php';

include_once 'src/Fmt/Additionals/UpgradeToPreg.php';

include_once 'src/Fmt/Additionals/WordWrap.php';

include_once 'src/Fmt/Additionals/WrongConstructorName.php';

include_once 'src/Fmt/Additionals/YodaComparisons.php';

include_once 'src/Fmt/cli-core.php';

include_once 'src/Fmt/cli-external.php';

include_once 'src/Fmt/Core/AddMissingCurlyBraces.php';

include_once 'src/Fmt/Core/AutoImport.php';

include_once 'src/Fmt/Core/BaseCodeFormatter.php';

include_once 'src/Fmt/Core/Cache.php';

include_once 'src/Fmt/Core/Cacher.php';

include_once 'src/Fmt/Core/CodeFormatter.php';

include_once 'src/Fmt/Core/constants.php';

include_once 'src/Fmt/Core/ConstructorPass.php';

include_once 'src/Fmt/Core/EliminateDuplicatedEmptyLines.php';

include_once 'src/Fmt/Core/ExternalPass.php';

include_once 'src/Fmt/Core/ExtraCommaInArray.php';

include_once 'src/Fmt/Core/FormatterPass.php';

include_once 'src/Fmt/Core/LeftAlignComment.php';

include_once 'src/Fmt/Core/MergeCurlyCloseAndDoWhile.php';

include_once 'src/Fmt/Core/MergeDoubleArrowAndArray.php';

include_once 'src/Fmt/Core/MergeParenCloseWithCurlyOpen.php';

include_once 'src/Fmt/Core/NormalizeIsNotEquals.php';

include_once 'src/Fmt/Core/NormalizeLnAndLtrimLines.php';

include_once 'src/Fmt/Core/RefactorPass.php';

include_once 'src/Fmt/Core/Reindent.php';

include_once 'src/Fmt/Core/ReindentColonBlocks.php';

include_once 'src/Fmt/Core/ReindentComments.php';

include_once 'src/Fmt/Core/ReindentEqual.php';

include_once 'src/Fmt/Core/ReindentObjOps.php';

include_once 'src/Fmt/Core/RemoveComments.php';

include_once 'src/Fmt/Core/ResizeSpaces.php';

include_once 'src/Fmt/Core/RTrim.php';

include_once 'src/Fmt/Core/SandboxedPass.php';

include_once 'src/Fmt/Core/SettersAndGettersPass.php';

include_once 'src/Fmt/Core/SplitCurlyCloseAndTokens.php';

include_once 'src/Fmt/Core/StripExtraCommaInList.php';

include_once 'src/Fmt/Core/SurrogateToken.php';

include_once 'src/Fmt/Core/Tree.php';

include_once 'src/Fmt/Core/TwoCommandsInSameLine.php';

include_once 'src/Fmt/csp.php';

include_once 'src/Fmt/helpers.php';

include_once 'src/Fmt/PSR/PSR1BOMMark.php';

include_once 'src/Fmt/PSR/PSR1ClassConstants.php';

include_once 'src/Fmt/PSR/PSR1ClassNames.php';

include_once 'src/Fmt/PSR/PSR1MethodNames.php';

include_once 'src/Fmt/PSR/PSR1OpenTags.php';

include_once 'src/Fmt/PSR/PSR2AlignObjOp.php';

include_once 'src/Fmt/PSR/PSR2CurlyOpenNextLine.php';

include_once 'src/Fmt/PSR/PSR2IndentWithSpace.php';

include_once 'src/Fmt/PSR/PSR2KeywordsLowerCase.php';

include_once 'src/Fmt/PSR/PSR2LnAfterNamespace.php';

include_once 'src/Fmt/PSR/PSR2ModifierVisibilityStaticOrder.php';

include_once 'src/Fmt/PSR/PSR2SingleEmptyLineAndStripClosingTag.php';

include_once 'src/Fmt/PSR/PsrDecorator.php';

include_once 'src/Fmt.php';

include_once 'src/Formatter.php';

include_once 'src/Log/composite.php';

include_once 'src/Log/console.php';

include_once 'src/Log/daemon.php';

include_once 'src/Log/display.php';

include_once 'src/Log/error_log.php';

include_once 'src/Log/file.php';

include_once 'src/Log/firebug.php';

include_once 'src/Log/mail.php';

include_once 'src/Log/mcal.php';

include_once 'src/Log/mdb2.php';

include_once 'src/Log/null.php';

include_once 'src/Log/observer.php';

include_once 'src/Log/sql.php';

include_once 'src/Log/sqlite.php';

include_once 'src/Log/syslog.php';

include_once 'src/Log/win.php';

include_once 'src/XML/Util.php';
use Contal\Formatter;

$uglyCode = file_get_contents('uglyCode.php');
$beautifulCode = Formatter::format($uglyCode);
$fp = fopen('beautifulCode.php', 'w');
fwrite($fp, $beautifulCode);
fclose($fp);
