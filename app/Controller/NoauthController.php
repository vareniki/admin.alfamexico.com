<?php

/**
 * Created by PhpStorm.
 * User: dmonje
 * Date: 16/04/14
 * Time: 09:39
 */

define('IMAGEN_X', 1280);

class NoauthController extends Controller {

  public $uses = array( 'Documento', 'Inmueble' );

  public $helpers = array( 'Inmuebles' );

  private static $tiposInmueble = array(
      '01' => 'departamento',
      '02' => 'casa',
      '03' => 'local',
      '04' => 'oficina',
      '05' => 'estacionamiento',
      '06' => 'terreno',
      '07' => 'nave',
      '08' => 'otro'
  );

  private static $calidadPrecio = array(
      '01' => 'muy bien',
      '02' => 'normal',
      '03' => 'caro'
  );

  function beforeFilter() {
    Configure::load( 'alfainmo' );
    App::import( 'Vendor', 'ImageTool' );
  }

    private function createThumb($origFile, $thumbFile, $thumbWidth) {

      $img = imagecreatefromjpeg($origFile);
      $width = imagesx($img);
      $height = imagesy($img);

      $new_width = $thumbWidth;
      $new_height = floor($height * ( $thumbWidth / $width ));

      $tmp_img = imagecreatetruecolor($new_width, $new_height);
      imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
      imagejpeg($tmp_img, $thumbFile);
    }

  /**
   * @param string $tam
   * @param null $img
   *
   * @return int
   */
  public function image( $tam = 'm', $img = null ) {

    $config = Configure::read( 'alfainmo' );
    $folder = $config ['images.path'];

    $watermark = strlen( $tam ) == 2 && substr( $tam, 1, 1 ) == 'w';

    if ( strlen( $tam ) >= 2 ) {
      $tam = substr( $tam, 0, 1 );
    }

    $this->layout     = null;
    $this->autoRender = false;

    $pref = ( $tam != 'o' ) ? $tam . '_' : '';

    if ( $img == null ) {

      header( 'Content-Type: image/png' );
      $this->response->type( 'image/png' );

      return readfile( $folder . $pref . 'sin_fotos.png' );

    } else {

      $img = str_replace( '|', '/', $img );
      $img = str_replace( '--', '/', $img );
      $ext = pathinfo( $img, PATHINFO_EXTENSION );

      $path = pathinfo( $img, PATHINFO_DIRNAME );
      if ( $path == '.' ) {
        $path = '';
      }

      $basename = pathinfo( $img, PATHINFO_BASENAME );
      $file = $folder . $path . DIRECTORY_SEPARATOR . $pref . $basename;
      $file_wm = $folder . $path . DIRECTORY_SEPARATOR . $pref . 'wm_' . $basename;
      $orig_file = $folder . $path . DIRECTORY_SEPARATOR . $basename;

      if (!file_exists($file) && file_exists($orig_file) && $pref == 'x_') {
        $this->createThumb($orig_file, $file, IMAGEN_X);
      }

      if ( file_exists($file) ) {

        header( "Content-Type: image/$ext" );
        $this->response->type( "image/$ext" );

        if ( $watermark ) {

          if (file_exists($file_wm) && filemtime($file_wm) < filemtime($file)) {
            unlink($file_wm);
          }

          if (!file_exists($file_wm)) {

            ImageTool::watermark([
                'input' => $file,
                'output' => $file_wm,
                'watermark' => 'img/watermark.png',
                'opacity'   => '15'
            ]);
          }

          if (file_exists($file_wm)) {
            $result = readfile($file_wm);
          } else {
            $result = readfile($file);
          }

        } else {
          $result = readfile($file);
        }
        return $result;

      } else {

        header( 'Content-Type: image/png' );
        $this->response->type( 'image/png' );

        return readfile( $folder . $pref . 'sin_fotos.png' );
      }
    }
  }

  /**
   * @param $id
   * @param $inmuebleId
   *
   * @return int
   */
  public function document( $id, $inmuebleId ) {

    $doc = $this->Documento->find( 'first', array(
        'conditions' => array(
            'Documento.id'          => $id,
            'Documento.inmueble_id' => $inmuebleId
        )
    ) );
    if ( empty( $doc ) ) {
      return;
    }

    $config = Configure::read( 'alfainmo' );

    $doc_path = $config['documents.path'] . $doc['Documento']['path'] . DIRECTORY_SEPARATOR;
    $doc_name = $doc['Documento']['fichero'];

    $this->layout     = null;
    $this->autoRender = false;

    header( 'Content-Type: ' . $doc['Documento']['tipo'] );
    header( 'Content-Disposition: attachment; filename="' . $doc['Documento']['nombre'] . '"' );

    //$this->response->type('application/octet-stream');

    return readfile( $doc_path . $doc_name );
  }

  /**
   * @param $id
   */
  public function inmueble( $id ) {
    // Llama a la función específica en función del tipo de inmueble actual
    $this->layout = null;
    //$this->autoRender = false;

    $info = $this->Inmueble->find( 'first', array( 'conditions' => array( 'Inmueble.id' => $id ), 'recursive' => 2 ) );
    $this->set( 'info', $info );

    $tipoInmueble = self::$tiposInmueble[ $info['Inmueble']['tipo_inmueble_id'] ];
    $this->set( 'tipoInmueble', $tipoInmueble );
    $this->set( 'calidadPrecio', self::$calidadPrecio );
  }
} 