<?php
Yii::import( 'zii.widgets.jui.CJuiDatePicker' );
class XJuiDatePicker extends CJuiDatePicker
{

    const ASSETS_NAME = '/jquery-ui-timepicker-addon';
    const LOCALIZATION_NAME = '/localization/jquery-ui-timepicker-';

    public $language;

    public $i18nScriptFile = 'jquery-ui-i18n.min.js';

    public $defaultOptions;

    public $mode = 'date';

    public $timeOptions = array();

    public $timeHtmlOptions = array();

    public $range;

    public function init()
    {
        if( !in_array( $this->mode, array( 'date', 'time', 'datetime' ) ) )
            throw new CException( 'unknown mode "' . $this->mode . '"' );
        if( !isset( $this->language ) )
            $this->language = Yii::app()->getLanguage();
        // Overwrite options for time picker
        if( $this->mode === 'time' )
        {
            $this->options = array_merge( $this->options, $this->timeOptions );
            $this->htmlOptions = array_merge( $this->htmlOptions, $this->timeHtmlOptions );
        }
        return parent::init();
    }

    public function run()
    {
        list($name,$id)=$this->resolveNameID();
        if(isset($this->htmlOptions['id']))
            $id=$this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];
        else
            $this->htmlOptions['name']=$name;

        $options=array();
        $maxLimit=$minLimit="";
        if($this->mode=='date')
        {
            $maxLimit=" if(limitDate)
                            $(inst.input).{$this->mode}picker('option','maxDate',limitDate);";
            $minLimit=" if(limitDate)
                            $(inst.input).{$this->mode}picker('option','minDate',limitDate);";
        }
        else if($this->mode=='time')
        {
             $maxLimit="$(inst.input).{$this->mode}picker('option','hourMax',hour);";
             $minLimit="$(inst.input).{$this->mode}picker('option','hourMin',hour);";
        }
        else if($this->mode=='datetime')
        {
            $maxLimit=" if(limitDate)
                            $(inst.input).{$this->mode}picker('option','maxDate',limitDate);
                        $(inst.input).{$this->mode}picker('option','hourMax',hour);";
            $minLimit=" if(limitDate)
                            $(inst.input).{$this->mode}picker('option','minDate',limitDate);
                        $(inst.input).{$this->mode}picker('option','hourMin',hour);";
        }
        if($this->range!=null)
        {
            $this->options['beforeShow']="js:function(input,inst){
                       $('.{$this->range}').each(function(index,elm){
                          if(index==0)
                            from=elm;
                          if(index==1)
                            to=elm;
                       });
                    if(to.id==input.id)
                        to=null;
                    if(from.id==input.id)
                        from=null;
                    if(to){
                        temp=new Date($(to).val())
                        year=temp.getFullYear();
                        month=temp.getMonth()+1;
                        date=temp.getDate();
                        hour=temp.getHours();
                        limitDate=year+'-'+month+'-'+date;
                        {$maxLimit}
                    }
                    if(from){
                        temp=new Date($(from).val())
                        year=temp.getFullYear();
                        month=temp.getMonth()+1;
                        date=temp.getDate();
                        hour=temp.getHours();
                        limitDate=year+'-'+month+'-'+date;
                        {$minLimit}
                    }
                }
";

          $options=CJavaScript::encode($this->options);
          $js = "jQuery('.{$this->range}').{$this->mode}picker($options);";
        }
        else
        {
          $options=CJavaScript::encode($this->options);
          $js = "jQuery('#{$id}').{$this->mode}picker($options);";
        }

        if ($this->language!='' && $this->language!='en_us')
        {
            $this->registerScriptFile($this->i18nScriptFile);
            if($this->range)
            {
              $js = "jQuery('.{$this->range}').{$this->mode}picker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$this->language}'], {$options}));";
            }
            else
            {
              $js = "jQuery('#{$id}').{$this->mode}picker(jQuery.extend({showMonthAfterYear:false}, jQuery.datepicker.regional['{$this->language}'], {$options}));";
            }
        }

        $cs = Yii::app()->getClientScript();
        $assets = Yii::app()->getAssetManager()->publish( Yii::getPathOfAlias('ext' ) . '/assets' );
        $cs->registerCssFile( $assets . self::ASSETS_NAME . '.css' );
        $cs->registerScriptFile( $assets . self::ASSETS_NAME . '.js',CClientScript::POS_END );
        $js.= "\n\$('body').ajaxSuccess(function(){".$js."})";

        if (isset($this->defaultOptions))
        {
            $this->registerScriptFile($this->i18nScriptFile);
            $cs->registerScript(__CLASS__,  $this->defaultOptions!==null?'jQuery.'.$this->mode.'picker.setDefaults('.CJavaScript::encode($this->defaultOptions).');':'');
        }
        $cs->registerScript(__CLASS__.'#'.$id, $js);
        $localization = $assets . self::LOCALIZATION_NAME . $this->language . '.js';
        if(file_exists( Yii::getPathOfAlias( 'webroot' ) . $localization ))
            $cs->registerScriptFile( $localization, CClientScript::POS_END );
    }
}
