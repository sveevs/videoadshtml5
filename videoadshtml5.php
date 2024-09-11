<?php
/*
Plugin Name: VideoADSHtml5
Version: 2.5
Plugin URI: https://sv-pt.ru/?p=858
Author: -sv-
Author URI: https://sv-pt.ru
Description: VideoADSHtml5 Плагин для вставки рекламы видео ролик
Text Domain: VideoADSHtml5
Domain Path: /languages
 */

error_reporting(error_reporting() & ~E_NOTICE);
//error_reporting(E_ALL & ~E_NOTICE);
 

if (!defined('ABSPATH')) {
    exit;
}

$href = 'href.txt';
//ip что бы у разных клиентов не открывалось одно и то же видео
$ip = $_SERVER['REMOTE_ADDR'];

//$hrefdir = trailingslashit(dirname( __FILE__ ))."/HTML5/href/";


if (!class_exists('VIDEO_HTML5_ADS')) {

    class VIDEO_HTML5_ADS {

        public static $plugin_version = '2.5';
		public static $url_razrab = 'https://sv-pt.ru';
		// var $plugin_version = '2.3.3';
		// var $url_razrab = 'https://sv-pt.ru';

        function __construct() {
            define('VIDEO_HTML5_ADS_VERSION', $this->plugin_version);
            $this->plugin_includes();
        }

        function plugin_includes() {
            if (is_admin()) {
                add_filter('plugin_action_links', array($this, 'videhtml5ads_plugin_action_links'), 10, 2);
            }
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('wp_enqueue_scripts', 'video_html5_ads_enqueue_scripts');
            add_action('admin_menu', array($this, 'video_html5_ads_add_options_menu'));
            add_shortcode('videoads', 'evp_embed_video_handler');
            add_filter('widget_text', 'do_shortcode');
            add_filter('the_excerpt', 'do_shortcode', 11);
            add_filter('the_content', 'do_shortcode', 11);
			add_filter('plugin_row_meta', array( &$this, 'videhtml5ads_setting_links' ), 1, 2 );
        }

	
		function videhtml5ads_setting_links($links, $file) {
			if (false === strpos($file, basename(__FILE__)))
				return $links;
			$links[] = '<a href="' . add_query_arg(array('page' => 'videhtml5ads-settings'), admin_url('options-general.php')) . '">' . __('&#128736; Настройки') . '</a>';
			$links[] = '<a href="https://sv-pt.ru/?p=858" target="_blank">' . __('&#128276; Перейти к документации') . '</a>';
			$links[] = '<a target="_blank" href="https://wordpress.org/support/plugin/VideoADSHtml5/reviews/">' . __('&#128205; Проголосовать ★★★★★','VideoADSHtml5') . '</a>';
				return $links;
		}
	

        function plugin_url() {
            if ($this->plugin_url)
                return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }

        function video_html5_ads_plugin_action_links($links, $file) {
            if ($file == plugin_basename(dirname(__FILE__) . '/videhtml5ads.php')) {
                $links[] = '<a href="options-general.php?page=videhtml5ads-settings">'.__('Settings', 'videhtml5ads').'</a>';
            }
            return $links;
        }
        
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('videhtml5ads', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }

        function video_html5_ads_add_options_menu() {
            if (is_admin()) {
                add_options_page(__('VideoADShtml5', 'videhtml5ads'), __('VideoADShtml5', 'videhtml5ads'), 'manage_options', 'videhtml5ads-settings', array($this, 'video_html5_ads_options_page'));
            }
            add_action('admin_init', array(&$this, 'video_html5_ads_add_settings'));
        }

        function video_html5_ads_add_settings() {
            register_setting('videhtml5ads-settings-group', 'evp_enable_jquery');
			register_setting('videhtml5ads-settings-group', 'evp_schet');
			register_setting('videhtml5ads-settings-group', 'evp_download');
			register_setting('videhtml5ads-settings-group', 'evp_speed');
			register_setting('videhtml5ads-settings-group', 'evp_pict');		
			register_setting('videhtml5ads-settings-group', 'evp_volume');	
			
        }

        function video_html5_ads_options_page() {
            $url = "https://sv-pt.ru/?p=858";
            $link_text = sprintf(wp_kses(__('Для получения подробной документации посетите домашнюю страницу плагина <a target="_blank" href="%s">здесь</a>.', 'videhtml5ads'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));          
            ?>
			
			
            <div class="wrap">
				
                <h1>Video ADS HTML5 - v <?php printf(esc_html__(VIDEO_HTML5_ADS::$plugin_version,'videhtml5ads')); ?></h1>
                <div class="update-nag"><?php printf(wp_kses_post($link_text,'videhtml5ads'));?></div>
                    <h2 class="title"><?php _e('Основные настройки', 'videhtml5ads')?></h2>		
                        <form method="post" action="options.php">
                            <?php settings_fields('videhtml5ads-settings-group'); ?>
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php _e('Включить jQuery', 'videhtml5ads')?></th>
                                    <td><input type="checkbox" id="evp_enable_jquery" name="evp_enable_jquery" value="1" <?php printf(wp_kses_post(checked(1, get_option('evp_enable_jquery'), false))) ?> /> 
                                        <p><i><?php _e('По умолчанию эта опция всегда должна быть отмечена..', 'videhtml5ads')?></i></p>
                                    </td>
                                </tr>
								<tr valign="top">
                                    <th scope="row"><?php _e('Продолжительность рекламы', 'videhtml5ads')?></th>
                                    <td><input type="text" id="evp_schet" name="evp_schet" value="<?php printf(wp_kses_post(get_option('evp_schet'))); ?>"> 
                                        <p><i><?php _e('Укажите продолжительность рекламы через сколько можно будет пропутить.', 'videhtml5ads')?></i></p>
                                    </td>
                                </tr>
                            </table>
											
							<hr style="margin: 20px 0;padding: 0;height: 0;	border: none;border-top: 2px dashed #ddd;">
														
							<h2 class="title"><?php _e('Настройки кнопок', 'videhtml5ads')?></h2>		
							
							 <table class="form-table">
                               
								<tr valign="top">
                                    <th scope="row"><?php _e('Скачиваните с плеера', 'videhtml5ads')?></th>
										<td>
											  <input type="radio" id="evp_download" name="evp_download" value="1" <?php printf(wp_kses_post(checked(1, get_option('evp_download')))); ?>> Оставить
											  <input type="radio" id="evp_download" name="evp_download" value="2" <?php printf(wp_kses_post(checked(2, get_option('evp_download')))); ?>> Убрать
											<p><i><?php _e('По умолчанию опция Оставить', 'videhtml5ads')?></i></p>		
										</td>	
                                </tr>
								<tr valign="top">
                                    <th scope="row"><?php _e('Скорость воспроизведения ', 'videhtml5ads')?></th>
										<td>
											    <input type="radio" id="evp_speed" name="evp_speed" value="1" <?php printf(wp_kses_post(checked(1, get_option('evp_speed')))); ?>> Оставить
												<input type="radio" id="evp_speed" name="evp_speed" value="2" <?php printf(wp_kses_post(checked(2, get_option('evp_speed')))); ?>> Убрать
											<p><i><?php _e('По умолчанию опция Оставить', 'videhtml5ads')?></i></p>
										</td>	
                                </tr>
										
								
								<tr valign="top">
                                    <th scope="row"><?php _e('Картинка в картинке ', 'videhtml5ads')?></th>
										<td>
												<input type="radio" id="evp_pict" name="evp_pict" value="1" <?php printf(wp_kses_post(checked(1, get_option('evp_pict')))); ?>> Оставить
												<input type="radio" id="evp_pict" name="evp_pict" value="2" <?php printf(wp_kses_post(checked(2, get_option('evp_pict')))); ?>> Убрать
												<p><i><?php _e('По умолчанию опция Оставить', 'videhtml5ads')?></i></p>
											
										</td>	
                                </tr>
								
								<tr valign="top">
                                    <th scope="row"><?php _e('Громкость ', 'videhtml5ads')?></th>
										<td>
											<input type="range" min="0.0" max="1" step="0.1" id="evp_volume" name="evp_volume" onchange="document.getElementById('rangeValue').innerHTML = this.value;" value="<?php printf(wp_kses_post(get_option('evp_volume'))); ?>"><span id="rangeValue"><?php printf(wp_kses_post(get_option('evp_volume'))); ?></span></p>
												<p><i><?php _e('Громкость при загрузке страницы', 'videhtml5ads')?></i></p>
											
										</td>	
                                </tr>
								
                            </table>
													
                            <p class="submit">
                                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                            </p>
                        </form>
            </div>
			
			
		
            <?php
        }

    }
    $GLOBALS['video_html5_ads'] = new VIDEO_HTML5_ADS();
}

function video_html5_ads_enqueue_scripts() {
    if (!is_admin()) {
        $plugin_url = plugins_url('', __FILE__);
        $enable_jquery = get_option('evp_enable_jquery');
        if ($enable_jquery) {
            wp_enqueue_script('jquery');
        }
		 $schet = get_option('evp_schet');
		 $volume = get_option('evp_volume');
		 wp_register_script('jquery',$plugin_url. 'https://code.jquery.com/jquery-3.7.1.js');
		 wp_enqueue_script('jquery');
		 wp_register_script('vast',$plugin_url. '/HTML5/js/vastvideoplugin.js');
		 wp_enqueue_script('vast');
		 wp_register_style('vast-css',$plugin_url. '/HTML5/css/videoadshtml5.css');
         wp_enqueue_style('vast-css');
		 wp_localize_script('vast', 'php_vars', array( 'schet' => $schet ) );
		 wp_localize_script('vast', 'php_vars_vol', array( 'volume' => $volume ) );
		 //wp_localize_script('vast', 'php_vars', array( 'volume' => $volume ) );
		 
		 //$hrefdir = trailingslashit(dirname( __FILE__ ))."/HTML5/href/";
		 
		 if (file_exists(dirname( __FILE__ ) )."HTML5/href/".$GLOBALS['ip'].$GLOBALS['href'])
			 {
			 
				$silki = file_get_contents(trailingslashit( dirname( __FILE__ ) )."HTML5/href/".$GLOBALS['ip'].$GLOBALS['href']);
				$silki = explode(PHP_EOL, $silki);
			 }
				else 
				{
					file_put_contents(trailingslashit( dirname( __FILE__ ) )."HTML5/href/".$GLOBALS['ip'].$GLOBALS['href'], $url."\n".$url1."\n".$url2."\n".$url3."\n".$url4."\n".$url5."\n".$url6."\n".$url7);

					$silki = file_get_contents(trailingslashit( dirname( __FILE__ ) )."HTML5/href/".$GLOBALS['ip'].$GLOBALS['href']);
					$silki = explode(PHP_EOL, $silki);
				}
		

		 wp_localize_script('vast', 'php_vars1', array( 'silki' => $silki[0] ) );
		 wp_localize_script('vast', 'php_vars2', array( 'silki' => $silki[1] ) );
		 wp_localize_script('vast', 'php_vars3', array( 'silki' => $silki[2] ) );
		 wp_localize_script('vast', 'php_vars4', array( 'silki' => $silki[3] ) );
		 wp_localize_script('vast', 'php_vars5', array( 'silki' => $silki[4] ) );
		 wp_localize_script('vast', 'php_vars6', array( 'silki' => $silki[5] ) );
		 wp_localize_script('vast', 'php_vars7', array( 'silki' => $silki[6] ) );
		 wp_localize_script('vast', 'php_vars8', array( 'silki' => $silki[7] ) );
		 

		 

    }
}
function evp_embed_video_handler($atts) {
    extract(shortcode_atts(array(
        'url' => '',
		'url1' => '',
		'url2' => '',
		'url3' => '',
		'url4' => '',
		'url5' => '',
		'url6' => '',
		'url7' => '',
        'ads' =>'',
		'ads_end' =>'',
        'width' => '',
        'height' => '',
		'ratio' => '0',
		'volume' =>'',
        'autoplay' => 'false',
        'poster' => '',
        'loop' => '',
        'muted' => '',
        'preload' => 'metadata',
        'share' => 'true',
        'video_id' => '',
        'class' => '',
        'template' => '',
    ), $atts));
    //check if mediaelement template is specified

    if($template=='mediaelement'){
        $attr = array();
        $attr['src'] = $url;
		$attr['src1'] = $url1;
		$attr['src2'] = $url2;
		$attr['src3'] = $url3;
		$attr['src4'] = $url4;
		$attr['src5'] = $url5;
		$attr['src6'] = $url6;
		$attr['src7'] = $url7;
        $attr['ads'] = $ads;
		$attr['ads_end'] = $ads_end;
	

        if(is_numeric($width)){
            $attr['width'] = $width;
        }
        if(is_numeric($height)){
            $attr['height'] = $height;
        }
        if ($autoplay == "true"){
            $attr['autoplay'] = 'on';
        }
        if ($loop == "true"){
            $attr['loop'] = 'on';
        }
        if (!empty($poster)){
            $attr['poster'] = $poster;
        }
        if (!empty($preload)){
            $attr['preload'] = $preload;
        }
        return wp_video_shortcode($attr);
    }

    if(!empty($video_id)){
        $video_id = ' id="'.$video_id.'"';
    }

    if ($autoplay == "true") {
        $autoplay = " autoplay";
    } else {
        $autoplay = "";
    }

    if ($loop == "true") {
        $loop= " loop";
    }
    else{
        $loop= "";
    }

    if($muted == "true"){
        $muted = " muted";
    }
    else{
        $muted = "";
    }
    
	//Кнопки
		$hrefdir = trailingslashit(dirname( __FILE__ ))."/HTML5/href/";
		
		$plugin_version = VIDEO_HTML5_ADS::$plugin_version;
		$url_razrab = VIDEO_HTML5_ADS::$url_razrab;
	
	
		$time_dir = filemtime($hrefdir);
		$time_new = time();		 
		//$time_dir60 = $time_dir + 7200; //+2 часа
		$time_dir60 = $time_dir + 3600; //+1 час до удаления файлов
		// Открываем файл для получения существующего содержимого
		//$current = file_get_contents($href);
		// Добавляем нового человека в файл
		// Пишем содержимое обратно в файл
		//file_put_contents($href, $current1);
		//Проверка на старые файлы с ip и сылками
		
	
	//Убираем кнопку Скачать
	$evp_download = get_option('evp_download');
	if ($evp_download == 1) 
	{
		$SettingDownload = "";
	}
	if ($evp_download == 2) 
	{
		$SettingDownload = "nodownload";
	}
 	
	//Убираем кнопку Скорость воспроизведения
	$evp_speed = get_option('evp_speed');
	if ($evp_speed == 1) 
	{
		$SettingSpeed = "";
	}
	if ($evp_speed == 2) 
	{
		$SettingSpeed = "noplaybackrate";
	}
	//Убираем кнопку Картинка в картинке
	$evp_pict = get_option('evp_pict');
	if ($evp_pict == 1) 
	{
		$SettingPict = "";
	}
	if ($evp_pict == 2) 
	{
		$SettingPict = "disablepictureinpicture";
	}

	
		
		if ($time_new > $time_dir60)
		{
			foreach (glob($hrefdir."/*") as $filedir)
			unlink($filedir);
			file_put_contents($hrefdir.$GLOBALS['ip'].$GLOBALS['href'], $url."\n".$url1."\n".$url2."\n".$url3."\n".$url4."\n".$url5."\n".$url6."\n".$url7);
				
		} 	else
			{
				file_put_contents($hrefdir.$GLOBALS['ip'].$GLOBALS['href'], $url."\n".$url1."\n".$url2."\n".$url3."\n".$url4."\n".$url5."\n".$url6."\n".$url7);
			}
	
	
		
	if($url1 == null){
	}
		else 
		{

		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					</div>  
					<div class='BtnBlockRight'>  
					  <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
					</div>
				  </div>";
		
		}
	
	if($url2 == null){
		
	}
		else 
		{
		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					  <div class='BtnVar3'>3 видео</div>
					</div>  
					<div class='BtnBlockRight'>  
					  <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
					</div>
				  </div>";
	
		}
		
	if($url3 == null){
		
	}
		else 
		{
		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					  <div class='BtnVar3'>3 видео</div>
					  <div class='BtnVar4'>4 видео</div>
					</div>
					<div class='BtnBlockRight'>
					  <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
					</div>
				  </div>";
		}	
		
	if($url4 == null){
		
	}
		else 
		{
		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					  <div class='BtnVar3'>3 видео</div>
					  <div class='BtnVar4'>4 видео</div>
					  <div class='BtnVar5'>5 видео</div>
					</div>  
					<div class='BtnBlockRight'>
					  <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
					 </div>
				  </div>";
		
		}
	if($url5 == null){
		
	}
		else 
		{
		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					  <div class='BtnVar3'>3 видео</div>
					  <div class='BtnVar4'>4 видео</div>
					  <div class='BtnVar5'>5 видео</div>
					  <div class='BtnVar6'>6 видео</div>
				    </div>
				    <div class='BtnBlockRight'>
				      <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
				    </div>
				  </div>";
		
		}

	if($url6 == null){
		
	}
		else 
		{
		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					  <div class='BtnVar3'>3 видео</div>
					  <div class='BtnVar4'>4 видео</div>
					  <div class='BtnVar5'>5 видео</div>
					  <div class='BtnVar6'>6 видео</div>
					  <div class='BtnVar7'>7 видео</div>
				    </div>
				    <div class='BtnBlockRight'>
				      <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
				    </div>
				  </div>";
		
		}

	if($url7 == null){
		
	}
		else 
		{
		$buttons="<div class='BtnBlock'>
					<div class='BtnBlockLeft'>
					  <div class='BtnVar1'>1 видео</div>
					  <div class='BtnVar2'>2 видео</div>
					  <div class='BtnVar3'>3 видео</div>
					  <div class='BtnVar4'>4 видео</div>
					  <div class='BtnVar5'>5 видео</div>
					  <div class='BtnVar6'>6 видео</div>
					  <div class='BtnVar7'>7 видео</div>
					  <div class='BtnVar8'>8 видео</div>
				    </div>
				    <div class='BtnBlockRight'>
				      <a title='Cайт разработчика плеера https://sv-pt.ru' rel='ugc' href='".$url_razrab."'>
					    <div class='urldirect' title='Посетить сайт разработчика плеера'>&#10067;</div>
					  </a>
				    </div>
				  </div>";
		
		}			
		
	$adsvast = <<<EOT
<VAST version="2.0">
<Ad id="pre-roll-0">
<InLine>
<AdSystem>2.0</AdSystem>
<AdTitle>Sample</AdTitle>
<Impression/>
<Creatives>
<Creative sequence="1" id="2">
<Linear>
<Duration>00:02:00</Duration>
<AdParameters> </AdParameters>
<MediaFiles>
<MediaFile delivery="progressive" bitrate="400" type="video/mp4">
<URL>$ads</URL>
</MediaFile>
</MediaFiles>
</Linear>
</Creative>
</Creatives>
</InLine>
</Ad>
<Ad id="post-roll-0">
<InLine>
<AdSystem>2.0</AdSystem>
<AdTitle>Sample</AdTitle>
<Impression/>
<Creatives>
<Creative sequence="1" id="2">
<Linear>
<Duration>00:02:00</Duration>
<AdParameters> </AdParameters>
<MediaFiles>
<MediaFile delivery="progressive" bitrate="400" type="video/mp4">
<URL>$ads_end</URL>
</MediaFile>
</MediaFiles>
</Linear>
</Creative>
</Creatives>
</InLine>
</Ad>
</VAST>
EOT;
	
	 
	file_put_contents(trailingslashit( dirname( __FILE__ ) )."HTML5/vod/ads.xml", $adsvast);
		

    $player = "fp" . uniqid();
    $color = '';
    if (!empty($poster)) {
        $color = 'background: #000 url('.$poster.') 0 0 no-repeat;background-size: 100%;';
		$poster = ' poster='.$poster; 
    } else {
        $color = 'background-color: #000;';
    }
    $size_attr = "";
    if (!empty($width)) {
        $size_attr = "max-width: {$width}px;max-height: auto;";
    }
    if(!empty($class)){
        $class = " ".$class;
    }
    $styles = <<<EOT
    <style>
        #$player {
            $size_attr
            $color
        }
    </style>
EOT;
	$adsxml = plugins_url('', __FILE__);
	 $chet = get_option('evp_schet');

	if ($ads_end == "")
	{
	
    $output = <<<EOT
 <center>
 <div>
	<video id="videohtml5_video" {$autoplay} {$muted} {$poster} {$loop} {$preload} src="$url" width = "100%" height="100%" controls  
  ads = '{  "servers": [
                         {
                         "apiAddress": "$adsxml/HTML5/vod/ads.xml"
                         }
                        ],
            "schedule": [
                          {
                            "position": "pre-roll"
                          }
                        ]
        }' controlsList="{$SettingDownload} {$SettingSpeed} {$SettingPict}"></video>
</div>
<span class="skipBtn"><div class="divleft">Пропустить через</div> <div id="div_schet">замена</div> из $chet</span>
	
<div class="Btnw">{$buttons}</div>

</center>

<!--Контекстное меню-->
<ul class='contextmenu'><li>v. {$plugin_version}</li></ul>
	
<script>initAdsFor("videohtml5_video");</script>

EOT;
	//Блок только с конечной рекламой
	} elseif ($ads == "")
	{
	
    $output = <<<EOT

 <center>
 <div>
	<video  id="videohtml5_video" {$autoplay} {$muted} {$poster} {$loop} {$preload} src="$url" width = "100%" height="100%" controls  
  ads = '{  "servers": [
                         {
                         "apiAddress": "$adsxml/HTML5/vod/ads.xml"
                         }
                        ],
            "schedule": [
                          {
                            "position": "post-roll"
                          }
                        ]
        }' controlsList="{$SettingDownload} {$SettingSpeed} {$SettingPict}"></video>
</div>
<span class="skipBtn"><div class="divleft">Пропустить через</div> <div id="div_schet">замена</div> из $chet</span>
<div class="Btnw">{$buttons}</div>
</center>

<!--Контекстное меню-->
<ul class='contextmenu'><li>v. {$plugin_version}</li></ul>

<script>initAdsFor("videohtml5_video");</script>

EOT;

	}  
		//Блок без рекламы
		elseif (($ads == "") and ($ads_end == ""))
	{
	
    $output = <<<EOT
 <center>
 <div>
	<video id="videohtml5_video" {$autoplay} {$muted} {$poster} {$loop} {$preload} src="$url" width = "100%" height="100%" ></video>
</div>
<span class="skipBtn"><div class="divleft">Пропустить через</div> <div id="div_schet">замена</div> из $chet</span>
<div class="Btnw">{$buttons}</div>
</center>

<!--Контекстное меню-->
<ul class='contextmenu'><li>v. {$plugin_version}</li></ul>

<script>initAdsFor("videohtml5_video");</script>



EOT;
		
	} 
		//Блок только с двумя блоками рекламы
		else
		
		{
					
			 $output = <<<EOT
 <center>
 <div>
	<video onClick="startTimer()" id="videohtml5_video" {$autoplay} {$muted} {$poster} {$loop} {$preload} src="$url" width = "100%" height="100%" controls  
  ads = '{  "servers": [
                         {
                         "apiAddress": "$adsxml/HTML5/vod/ads.xml"
                         }
                        ],
            "schedule": [
                          {
                            "position": "pre-roll"
                          },
						  {
                            "position": "post-roll"
                          }
						  
                        ]
        }' controlsList="{$SettingDownload} {$SettingSpeed} {$SettingPict}"></video>
</div>

<span class="skipBtn"><div class="divleft">Пропустить через</div> <div id="div_schet">замена</div> из $chet</span>
		<div class="Btnw">{$buttons}Пропустить через</div>
</center>

<!--Контекстное меню-->
<ul class='contextmenu'><li>v. {$plugin_version}</li></ul>

<script>initAdsFor("videohtml5_video");</script>



EOT;
			
			
		}

    return $output;
}
