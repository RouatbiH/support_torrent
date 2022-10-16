<?php
if (!defined('IN_PLUGINS_SYSTEM')) {
    exit();
}
include __DIR__.'/Torrent.php';
$torrent = new Torrent(__DIR__.'/../../uploads/'.$file_info['name']);

$files = $torrent->content();

function parseInput($input) {
  $result = array();

  foreach ($input AS $path => $size) {
    $prev = &$result;

    $s = strtok($path, DIRECTORY_SEPARATOR);

    while (($next = strtok(DIRECTORY_SEPARATOR)) !== false) {
      if (!isset($prev[$s])) {
        $prev[$s] = array();
      }

      $prev = &$prev[$s];
      $s = $next;
    }

    $prev[] = $s;

    unset($prev);
  }

  return $result;
}

$folders = parseInput($files);

class treeview {

  private $files;
  
  function __construct( $files ) {
      $this->files = $files;
  }

  function create_tree( ) {
      
    if( count( $this->files ) ) {
      natcasesort( $this->files );

	function process($array) {
            $fileFolderList = $array;
            echo '<ul class="filetree" style="display: none;">';
            foreach ($fileFolderList as $key => $name) {
                    if (!is_array($name)) {
                        $ext = preg_replace('/^.*\./', '', $name);
                        echo '<li class="file ext_' . $ext . '"><a href="#" rel="File">' . htmlentities( $name ) . '</a></li>';
                    } else {
                        echo '<li class="folder expanded"><a href="#">' . htmlentities( $key ) . '</a>';
                    }
                    if (is_array($name)) {
                        process($name);
                    }
                    echo '</li>';
            }
            echo '</ul>';
	}

	process($this->files);
    }
  }
}

$tree = new treeview( $folders );
ob_start();
$tree->create_tree();
$torrentoutput = ob_get_contents();
ob_end_clean();
?>