<?php
# Kleeja Plugin
# support_torrent
# Version: 1.0
# Developer: Kleeja team

# Prevent illegal run
if (!defined('IN_PLUGINS_SYSTEM')) {
    exit();
}


# Plugin Basic Information
$kleeja_plugin['support_torrent']['information'] = array(
    # The casucal name of this plugin, anything can a human being understands
    'plugin_title' => array(
        'en' => 'Support Torrent',
        'ar' => 'دعم امتداد Torrent'
    ),
    # Who wrote this plugin?
    'plugin_developer' => 'Kleeja.net',
    # This plugin version
    'plugin_version' => '1.0',
    # Explain what is this plugin, why should I use it?
    'plugin_description' => array(
        'en' => 'Show torrent informations in download page',
        'ar' => 'عرض معلومات حول ملف Torrent في صفحة التحميل'
    ),
    # Min version of Kleeja that's requiered to run this plugin
    'plugin_kleeja_version_min' => '2.0',
    # Max version of Kleeja that support this plugin, use 0 for unlimited
    'plugin_kleeja_version_max' => '3.9',
    # Should this plugin run before others?, 0 is normal, and higher number has high priority
    'plugin_priority' => 10
);

//after installation message, you can remove it, it's not required
$kleeja_plugin['support_torrent']['first_run']['ar'] = "
شكراً لاستخدامك هذه الإضافة قم بمراسلتنا بالأخطاء عند ظهورها على البريد: <br>
info@kleeja.net
";

$kleeja_plugin['support_torrent']['first_run']['en'] = "
Thanks for using this plugin, to report bugs contact us: 
<br>
info@kleeja.net
";


# Plugin Installation function
$kleeja_plugin['support_torrent']['install'] = function ($plg_id)
{
//    //new language variables
//    add_olang(array(
//
//    ),
//        'ar',
//        $plg_id);
//
//    add_olang(array(
//
//    ),
//        'en',
//        $plg_id);
};


//Plugin update function, called if plugin is already installed but version is different than current
$kleeja_plugin['support_torrent']['update'] = function ($old_version, $new_version) {
    // if(version_compare($old_version, '0.5', '<')){
    // 	//... update to 0.5
    // }
    //
    // if(version_compare($old_version, '0.6', '<')){
    // 	//... update to 0.6
    // }

    //you could use update_config, update_olang
};


# Plugin Uninstallation, function to be called at unistalling
$kleeja_plugin['support_torrent']['uninstall'] = function ($plg_id) {
    //delete language variables
//    foreach (array('ar', 'en') as $language) {
//        delete_olang(null, $language, $plg_id);
//    }
};


# Plugin functions
$kleeja_plugin['support_torrent']['functions'] = array(

    'get_mime_for_header_func' => function ($args) {
        $ext = $args['ext'];

        if ($ext === 'torrent')
        {
            $return = 'application/x-bittorrent';
            return compact('return');
        }
    },

    'Saaheader_links_func' => function($args){
        global $config;

        $extra = $args['extra'];

        $header_codes = '<link rel="stylesheet" href="' . $config['siteurl'] . 'plugins/support_torrent/assets/filetree.css" type="text/css" >' . "\n" .
                        '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>' . "\n" .
                        '<script type="text/javascript" >
$(document).ready( function() {


    getfilelist( $(\'#container\') );
    
    function getfilelist( cont ) {
    


                $( cont ).find(\'UL:hidden\').slideDown({ duration: 500, easing: null });
            
    }
    
    $( \'#container\' ).on(\'click\', \'LI A\', function() {
        var entry = $(this).parent();
        
        if( entry.hasClass(\'folder\') ) {
            if( entry.hasClass(\'collapsed\') ) {
                        
                entry.find(\'UL:hidden\').slideDown({ duration: 500, easing: null });
                entry.removeClass(\'collapsed\').addClass(\'expanded\');
            }
            else {
                
                entry.find(\'UL\').slideUp({ duration: 500, easing: null });
                entry.removeClass(\'expanded\').addClass(\'collapsed\');
            }
        } else {
            $( \'#selected_file\' ).text( "File:  " + $(this).attr( \'rel\' ));
        }
    return false;
    });
    
});
</script>' . "\n";


        $extra .= $header_codes;

        return compact('extra');
    },

    /*'print_Saafooter_func' => function($args){
        $footer = $args['footer'];

        $footer = str_replace('</body>', "<script src=\"//vjs.zencdn.net/6.2.7/video.js\"></script>\n</body>", $footer);
        return compact('footer');
    },*/

    'style_parse_func' => function($args) {
        global $config,$file_info;
        if($args['template_name'] == 'download') {
            require __DIR__.'/Foldertree.php';
            $x = PHP_EOL . '<IF NAME="show_support_torrent_code">
                    <div class="mt-3">
                        <h4 style="word-wrap:break-word;">Torrent information</h4>
                    </div>
                    <div class="row">
					<div class="col-md-6 mt-2">
						<!-- Information Torrent -->
						<ul class="list-group">
							<li class="list-group-item d-flex justify-content-between flex-column">
								<span class="text-secondary">Torrent name</span>
								<div class="list-group-item-text break-all" style="word-wrap:break-word!important;">'.$torrent->name().'</div>
							</li>
							<li class="list-group-item d-flex justify-content-between">
								<span class="text-secondary">Magnet</span>
								<div class="list-group-item-text"><a href="'.$torrent->magnet().'"><img src="' . $config['siteurl'] . 'plugins/support_torrent/assets/images/magnet.png" alt="magnet"></a></div>
							</li>
							<li class="list-group-item d-flex justify-content-between">
								<span class="text-secondary">Torrent comment</span>
								<div class="list-group-item-text">'.$torrent->comment().'</div>
							</li>
							<li class="list-group-item d-flex justify-content-between">
								<span class="text-secondary">Torrent is private?</span>
								<div class="list-group-item-text">'.($torrent->is_private()?"Yes":"No").'</div>
							</li>
							<li class="list-group-item d-flex justify-content-between">
								<span class="text-secondary">Torrent hash</span>
								<div class="list-group-item-text">'.$torrent->hash_info().'</div>
							</li>
							<li class="list-group-item d-flex justify-content-between">
								<span class="text-secondary">Torrent size</span>
								<div class="list-group-item-text">'.$torrent->size(2).'</div>
							</li>
						</ul>
					</div>
					<!-- @end-Information-Torrent -->
					<!-- File Tree -->
					<div class="col-md-6 mt-2">
						<div class="jumbotron">
							<div id="container">'. $torrentoutput . '</div>
                            <div id="selected_file"></div>
						</div>
					</div>
				</div>
                  </IF>';

            $html = $args['html'] . $x;

            return compact('html');
        }
    },

    'b4_showsty_downlaod_id_filename' => function($args){
        global $config;

        $file_info = $args['file_info'];


        $show_support_torrent_code = false;
        $torrent_path = '';

        if(strtolower($file_info['type'])=="torrent"){

            $show_support_torrent_code = true;


            $torrent_path =  $config['siteurl'] . "{$file_info['folder']}/{$file_info['name']}";

            is_array($plugin_run_result = Plugins::getInstance()->run('plugin:support_torrent:do_display', get_defined_vars())) ? extract($plugin_run_result) : null; //run hook

        }

        return compact('show_support_torrent_code', 'torrent_path');
    }
);

