<?php
namespace MiMFa\Module;
use MiMFa\Library\Convert;
use MiMFa\Library\Style;
use MiMFa\Library\HTML;
class Field extends Module{
	public $Capturable = true;
	public $Template = null;
	/**
     * The key name of the field
     * @var string|null
     */
	public $Key = null;
	/**
     * Can be a datatype or an input type
     * @var object|string|null
     */
	public $Type = "text";
	/**
     * The default value of the field
     * @var mixed
	 */
	public $Value = null;
	/**
     * The default place holder of the field
     * @var string|null
     */
	public $PlaceHolder = null;
	/**
     * The other options of the fields like dropdowns
     * @var array|string|null
	 */
	public $Options = null;
	/**
     * Other important attributes of the field
     * @var array|string|null
	 */
	public $Attributes = null;
	public $ForeColor = "var(--ForeColor-1)";
	public $BackColor = "var(--BackColor-1)";
	public $BorderColor = "var(--ForeColor-5)";
	public $Height = "auto";
	public $Width = "100%";
	public $MinHeight = "10px";
	public $MinWidth = "auto";
	public $MaxHeight = "25vmin";
	public $MaxWidth = "100vw";
	/**
	 * The field is required or not
     * @var bool|null
	 */
	public $Required = false;
	/**
     * Indicate the field be static or changable
     * @var bool|null
	 */
	public $Lock = false;


	/**
     * Create the module
     * @param object|string|null $type Can be a datatype or an input type
     * @param string|null $title The label text of the field
     * @param mixed $value The default value of the field
     * @param mixed $description The more detaled text about the field
     * @param array|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param bool|null $lock Indicate the field be static or changable
     * @return Field
     */
	public function __construct($type = null, $title = null, $value = null, $description = null, $options = null, $attributes = null, $required = null, $lock = null, $key = null){
        parent::__construct();
		$this->Set($type, $title, $value, $description, $options,  $attributes, $required, $lock, $key);
    }
	/**
	 * Change the inputs tool
	 * @param object|string|null $type Can be a datatype or an input type
	 * @param string|null $title The label text of the field
     * @param mixed $value The default value of the field
     * @param mixed $description The more detaled text about the field
     * @param array|string|null $options The other options of the field
     * @param array|string|null $attributes Other important attributes of the field
     * @param bool|null $lock Indicate the field be static or changable
	 * @return Field
	 */
	public function Set($type = null, $title = null, $value = null, $description = null, $options = null, $attributes = null, $required = null, $lock = null, $key = null){
		if(!is_null($type)){
			if(is_string($type)) $this->Type = $type;
			else $this->Type = gettype($type);
			if(is_null($value) && is_null($title))
				$value = $type;
        } else $this->Type = null;
		$this->Title = $title;
		$this->Key = $key??Convert::ToName($this->Title);
		$this->Value = $value;
		$this->Description = $description;
		$this->Options = $options;
		$this->Attributes = $attributes;
		$this->Required = $required??($this->Required??!is_null($this->Value));
		$this->Lock = $lock??($this->Lock??!is_null($this->Value));
		$this->PlaceHolder = null;
		return $this;
    }

	public function GetStyle(){
		switch (strtolower($this->Template??"")) {
			case 'v':
			case 'vertical':
				return $this->GetVerticalStyle();
			case 'h':
			case 'horizontal':
				return $this->GetHorizontalStyle();
			default:
				return $this->GetDefaultStyle();
		}
	}

	public function GetDefaultStyle(){
		return HTML::Style("
			.{$this->Name}{
				".Style::DoProperty("min-width",$this->MinWidth)."
				".Style::DoProperty("min-height", $this->MinHeight)."
				".Style::DoProperty("max-width", $this->MaxWidth)."
				".Style::DoProperty("max-height", $this->MaxHeight)."
				".Style::DoProperty("width", $this->Width)."
				".Style::DoProperty("height", $this->Height)."
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} label.title{
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				vertical-align: top;
				margin-right: -1px;
				padding: 0px 10px;
				z-index: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field{
				".Style::DoProperty("color", $this->ForeColor)."
				".Style::DoProperty("background-color", $this->BackColor)."
				display: table-cell;
				font-size: 125%;
				width: 100%;
				border-radius: 3px;
				".Style::DoProperty("border", \_::$TEMPLATE->Border(1))."
				border-color: transparent;
				".Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0))."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} label.description{
				text-align: initial;
				display: block;
				font-size: 75%;
				padding: 5px;
				opacity: 0.5;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover .field{
				".Style::DoProperty("border-color", $this->BorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover label.description{
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}
	public function GetHorizontalStyle(){
		return HTML::Style("
			.{$this->Name}{
				".Style::DoProperty("min-width",$this->MinWidth)."
				".Style::DoProperty("min-height", $this->MinHeight)."
				".Style::DoProperty("max-width", $this->MaxWidth)."
				".Style::DoProperty("max-height", $this->MaxHeight)."
				".Style::DoProperty("width", $this->Width)."
				".Style::DoProperty("height", $this->Height)."
				font-size: var(--Size-1);
				text-align: start;
				display: table-row;
			}
			.{$this->Name} label.title{
				".Style::DoProperty("color", $this->ForeColor)."
				".Style::DoProperty("background-color", $this->BackColor)."
				width: fit-content;
				display: table-cell;
				position: relative;
				text-align: initial;
				vertical-align: middle;
				margin-right: -1px;
				padding: 0px 10px;
				border-radius: 3px 0px 0px 3px;
				".Style::DoProperty("border", \_::$TEMPLATE->Border(1))."
				border-color: transparent;
				border-right: 0px solid;
				z-index: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field{
				".Style::DoProperty("color", $this->ForeColor)."
				".Style::DoProperty("background-color", $this->BackColor)."
				display: table-cell;
				font-size: 125%;
				width: 100%;
				border-radius: 0px 3px 3px 0px;
				".Style::DoProperty("border", \_::$TEMPLATE->Border(1))."
				border-color: transparent;
				border-left: 0px solid;
				".Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0))."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} label.description{
				text-align: initial;
				vertical-align: middle;
				display: table-cell;
				font-size: 75%;
				padding: 0px 5px;
				opacity: 0;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover label.title{
				".Style::DoProperty("border-color", $this->BorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover .field{
				".Style::DoProperty("border-color", $this->BorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover label.description{
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}
	public function GetVerticalStyle(){
		return HTML::Style("
			.{$this->Name}{
				".Style::DoProperty("min-width",$this->MinWidth)."
				".Style::DoProperty("min-height", $this->MinHeight)."
				".Style::DoProperty("max-width", $this->MaxWidth)."
				".Style::DoProperty("max-height", $this->MaxHeight)."
				".Style::DoProperty("width", $this->Width)."
				".Style::DoProperty("height", $this->Height)."
				font-size: var(--Size-1);
				text-align: start;
			}
			.{$this->Name} label.title{
				".Style::DoProperty("color", $this->ForeColor)."
				".Style::DoProperty("background-color", $this->BackColor)."
				width: fit-content;
				display: flex;
				position: relative;
				font-size: 125%;
				text-align: initial;
				margin-bottom: -1px;
				padding: 2px;
				border-radius: 3px 3px 0px 0px;
				".Style::DoProperty("border", \_::$TEMPLATE->Border(1))."
				border-color: transparent;
				border-bottom: 0px solid;
				z-index: 1;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} .field{
				".Style::DoProperty("color", $this->ForeColor)."
				".Style::DoProperty("background-color", $this->BackColor)."
				font-size: 100%;
				width: 100%;
				border-radius: 0px 3px 3px 3px;
				".Style::DoProperty("border", \_::$TEMPLATE->Border(1))."
				border-color: transparent;
				".Style::DoProperty("border-radius", \_::$TEMPLATE->Radius(0))."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name} label.description{
				text-align: initial;
				display: block;
				font-size: 50%;
				margin-top: -1px;
				padding: 3px;
				opacity: 0;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover label.title{
				font-size: 75%;
				".Style::DoProperty("border-color", $this->BorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover .field{
				font-size: 125%;
				".Style::DoProperty("border-color", $this->BorderColor)."
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
			.{$this->Name}:hover label.description{
				font-size: 75%;
				opacity: 0.75;
				".Style::UniversalProperty("transition",\_::$TEMPLATE->Transition(1))."
			}
		");
	}

	public function Get(){
		return Convert::ToString(function(){
            yield $this->GetContent();
            $id = $this->Key.getID();
            if(isValid($this->Title)) yield "<label for='$id' class='title'>".__($this->Title, styling:false)."</label>";
            $placeHolder = __($this->PlaceHolder??$this->Title, styling:false);
            $placeHolderAttr = isValid($placeHolder)?"placeholder='$placeHolder'":"";
            $attrs = Convert::ToString($this->Attributes, " ");
            if(is_null($this->Type)){
                if(isEmpty($this->Value)) $type = "text";
                elseif(is_string($this->Value)){
                    if(isUrl($this->Value)) {
                        if(isFile($this->Value)) $type = "file";
						else $type = "url";
                    }
                    elseif(strlen($this->Value)>100||count(explode("\r\n\t\f\0",$this->Value))>1) $type = "strings";
                    else $type = "string";
                }else $type = strtolower(gettype($this->Value));
            }
            else $type = strtolower($this->Type);
            switch ($type) {
                case 'label':
                case 'span':
                    yield "<label id='$id' name='$this->Key' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>".Convert::ToString($this->Value)."</label>";
                    break;
                case 'object':
                case 'array':
                case 'lines':
                case 'texts':
                case 'strings':
                case 'multiline':
                case 'textarea':
                    yield "<textarea id='$id' name='$this->Key' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>".Convert::ToString($this->Value)."</textarea>";
                    break;
                case 'line':
                case 'text':
                case 'string':
                case 'singleline':
                    yield "<input id='$id' name='$this->Key' type='text' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'enum':
                case 'dropdown':
                case 'combobox':
                case 'select':
                    yield "<select id='$id' name='$this->Key' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    if(isValid($this->Options))
                        foreach($this->Options as $key=>$value)
                            yield "<option value='$key'".($key==$this->Value?" selected":"").">$value</option>";
                    yield "</select>";
                    break;
                case 'radio':
                case 'radiobox':
                case 'radiobutton':
                    yield "<input id='$id' name='$this->Key' type='radio' checked='".Convert::ToString($this->Value)."' value='$placeHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'bool':
                case 'boolean':
                case 'check':
                case 'checkbox':
                case 'checkbutton':
                    yield "<input id='$id' name='$this->Key' type='checkbox' checked='".Convert::ToString($this->Value)."' value='$placeHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'int':
                case 'integer':
                case 'short':
                case 'long':
                case 'number':
                    yield "<input id='$id' name='$this->Key' type='number' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'float':
                case 'double':
                case 'decimal':
                    yield "<input id='$id' name='$this->Key' type='number' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'phone':
                case 'tel':
                case 'telephone':
                    yield "<input id='$id' name='$this->Key' type='tel' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'url':
                case 'path':
                    yield "<input id='$id' name='$this->Key' type='url' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                case 'doc':
                case 'document':
                case 'image':
                case 'audio':
                case 'video':
                case 'file':
                    $p = null;
                    if(isValid($this->Value)){
                        MODULE("Player");
                        $p = new Player($this->Value);
                        $p->Style = new Style();
                        $p->Style->BackgroundColor = $this->BackColor;
                        $p->Style->Color = $this->ForeColor;
                        $p->Style->Border = "1px solid ". $this->BorderColor;
                        $p->MinWidth = $this->MinWidth??"auto";
                        $p->MinHeight = $this->MinHeight??$p->MinHeight;
                        $p->Height = $this->MaxHeight??"25vmin";
                        $p->Width = $this->Width??$p->Width;
                        $p->MaxWidth = $this->MaxWidth??"100%";
                        $p->MaxHeight = $this->MaxHeight??"25vmin";
                        $p->Id = "Player".getId();
                        if(!$this->Lock) $p->PrependControls = "
											<div class=\"fa fa-trash button\" onclick=\"
												document.getElementById('$id').setAttribute('disabled','disabled');
												document.getElementById('{$p->Id}').style.opacity='0.5';
												document.getElementById('{$p->Id}').style.borderColor='#f33';\"></div>
											<div class=\"fa fa-edit button\" onclick=\"
												document.getElementById('$id').removeAttribute('disabled');
												document.getElementById('{$p->Id}').style.opacity='1';
												document.getElementById('{$p->Id}').style.borderColor='{$this->BorderColor}';
												document.getElementById('$id').click();\"></div>";
                        yield $p->Capture();
                    }
                    $others = "";
                    $accept = "";
                    if(isValid($this->Options))
                        $accept = join("|", array_values($this->Options));
                    else switch ($type)
                        {
                            case 'doc':
                            case 'document':
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableDocumentFormats)."'";
                                $others = is_null($p)?"": HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].removeAttribute(attr);};");
                                break;
                            case "image":
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableImageFormats)."'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].removeAttribute(attr);};");
                                break;
                            case "audio":
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableAudioFormats)."'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].children[0].removeAttribute(attr);};");
                                break;
                            case "video":
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableVideoFormats)."'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].children[0].removeAttribute(attr);};");
                                break;
                            default:
                                $accept = "";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].removeAttribute(attr);};");
                                break;
                        }
                    yield "<input id='$id' name='$this->Key' type='file' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'$accept".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>"
                    .$others;
                    break;
                case 'docs':
                case 'documents':
                case 'images':
                case 'audios':
                case 'videos':
                case 'files':
                    $p = "";
                    if(isValid($this->Value)){
                        MODULE("Player");
                        $p = new Player($this->Value);
                        $p->Style = new Style();
                        $p->Style->BackgroundColor = $this->BackColor;
                        $p->Style->Color = $this->ForeColor;
                        $p->Style->Border = "1px solid ". $this->BorderColor;
                        $p->MinWidth = $this->MinWidth??"auto";
                        $p->MinHeight = $this->MinHeight??$p->MinHeight;
                        $p->Height = $this->MaxHeight??"25vmin";
                        $p->Width = $this->Width??$p->Width;
                        $p->MaxWidth = $this->MaxWidth??"100%";
                        $p->MaxHeight = $this->MaxHeight??"25vmin";
                        $p->Id = "Player".getId();
                        if(!$this->Lock) $p->PrependControls = "
											<div class=\"fa fa-trash button\" onclick=\"
												document.getElementById('$id').setAttribute('disabled','disabled');
												document.getElementById('{$p->Id}').style.opacity='0.5';
												document.getElementById('{$p->Id}').style.borderColor='#f33';\"></div>
											<div class=\"fa fa-edit button\" onclick=\"
												document.getElementById('$id').removeAttribute('disabled');
												document.getElementById('{$p->Id}').style.opacity='1';
												document.getElementById('{$p->Id}').style.borderColor='{$this->BorderColor}';
												document.getElementById('$id').click();\"></div>";
                        yield $p->Capture();
                    }
                    $others = "";
                    $accept = "";
                    if(isValid($this->Options))
                        $accept = join("|", array_values($this->Options));
                    else switch ($type)
                        {
                            case 'docs':
                            case 'documents':
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableDocumentFormats)."'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].removeAttribute(attr);};");
                                break;
                            case "images":
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableImageFormats)."'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].removeAttribute(attr);};");
                                break;
                            case "audios":
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableAudioFormats)."*'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].children[0].removeAttribute(attr);};");
                                break;
                            case "videos":
                                $accept = " accept='".join(", ",\_::$CONFIG->AcceptableVideoFormats)."'";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].children[0].removeAttribute(attr);};");
                                break;
                            default:
                                $accept = "";
                                $others = is_null($p)?"":HTML::Script("document.getElementById('$id').onchange = function () { if(this.files.length > 0) for(attr of ['src', 'alt']) document.getElementById('{$p->Id}').children[1].children[0].removeAttribute(attr);};");
                                break;
                        }
                    yield "<input id='$id' name='$this->Key' type='file' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'$accept".($this->Lock?" disabled":"").($this->Required?" required":"")." multiple $attrs>"
                    .$others;
                    break;
                case "dir":
                case "directory":
                case "folder":
                    yield "<input id='$id' name='$this->Key' type='file' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." webkitdirectory multiple $attrs>";
                    break;
                case 'imagesubmit':
                case 'imgsubmit':
                    yield "<input id='$id' name='$this->Key' type='image' src='".Convert::ToString($this->Value)."' value='$placeHolder' class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
                default:
                    yield "<input id='$id' name='$this->Key' type='$type' value='".Convert::ToString($this->Value)."' $placeHolderAttr class='field'".($this->Lock?" disabled":"").($this->Required?" required":"")." $attrs>";
                    break;
            }
            if(isValid($this->Description)) yield "<label for='$id' class='description'>".__($this->Description)."</label>";
        });
    }
}
?>