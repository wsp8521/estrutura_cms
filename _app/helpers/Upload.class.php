<?php

/**
 * <b>Upload.class</b>[HELPERS]
 * Descrição: Classe responsavel pela gestão de upload de imagens, arquivos e midias do sistema
 * @author Wesley Santos Pereira 
 * @copyright (c) 2017, Wtec Soluções Web
 */
class Upload {
    /* ATRIBUTOS GENERICOS */

    private $File; //recebe o aquivo
    private $Name; //nome do arquivo
    private $Send;  //caminho do arquivo

    /* ATRIBUTOS DE IMAGENS */
    private $ImgW; //largura da imagem
    private $Image; //recebe uma imagem;

    /* ATRIBUTOS PARA THUMBNAIL */
    private $FolderThumb; //pasta onde será armazenada as miniaturas
    private $NameThumb; // nome da thumbnail
    private $ImgWThumb; //largura da imagem
    private $SendThumb; //caminha da thumbnail
    private $ResultThumb; //resultado da thumb

    /* ATRIBUTOS DE ERROS */
    private $Result; //retorna um resultado;
    private $Error; //retorna uma mensagem de erro

    /* ATRIBUTOS DE FOLDERS */
    private $Folder; //subpasta
    private static $BaseDir; //pasta padrão

    /**
     * ****************************************
     * ************ PUBLICMETHODS *************
     * ****************************************
     */
    //comportamento inicial da classe. Recebe e cria a pasta padrão

    function __construct($baseDir = null) {
        self::$BaseDir = ($baseDir ? $baseDir : '../upload/');
        if (!file_exists(self::$BaseDir) && !is_dir(self::$BaseDir)):
            mkdir(self::$BaseDir, 0777);
        endif;
    }

    /**     *
     * <b>uploadImage</b>
     * Metódo responsalvel pelo upload de imagem
     * @param array $img - recebe os dados da imagems
     * @param string $name - recebe o nome da imagem
     * @param string $folder - pasta onde as imagem serão armazenadas. Default: imagem
     * @param string $width - largura da imagem. Default 1024
     */
    public function uploadImage(array $img, $name = null, $folder = null, $width = null) {
        //excluindo a estençao do nome do arquo
        $this->File = $img;
        $this->Name = ($name ? $name : substr($img['name'], 0, strpos($img['name'], '.')));
        $this->Folder = ($folder ? "{$folder}/" : 'imagem/');
        $this->ImgW = ((int) $width ? $width : 1024);
        $this->createFolder($this->Folder);
        $this->checkFileName(1);
        $this->exeUploadImage(1);
    }

    public function createThumbnail($folder = null, $widthThumb = null) {
        $this->FolderThumb = ($folder ? "{$folder}/" : 'thumb/');
        $this->ImgWThumb = (int) $widthThumb ? $widthThumb : 150;
        $this->createFolder($this->Folder);
        $this->checkFileName(2);
        $this->exeUploadImage(2);
    }

    /**     *
     * <b>uploadFile</b>
     * Metódo resposanvel pelo upload de aquivos
     * @param array $file - recebe o arquivo
     * @param string $name - recebe o nome do arquivo
     * @param string $folder - pasta onde será armazendaa o arquivo. Default: file
     * @param int $maxSizeFile - tamanho do arquivo em MB. Default: 2MB
     */
    public function uploadFile(array $file, $name = null, $folder = null, $maxSizeFile = null) {
        $this->File = $file;
        $this->Name = ($name ? $name : substr($file['name'], 0, strpos($file['name'], '.')));
        $this->Folder = ($folder ? $folder : 'file/');
        $maxSizeFile = (int) $maxSizeFile ? $maxSizeFile : 2; //tamanho do aquivo em MB
        //difinindo os tipos de arquivos
        $typeFile = ['text/plain', //txt
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', //docx
            'application/vnd.openxmlformats-officedocument.presentationml.presentation', //pptx
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', //xls
            'application/pdf', //pdf
        ];

        //aplicando validações
        if ($this->File['size'] > ($maxSizeFile * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito grande. Envie um arquivo com tamanho máximo de {$maxSizeFile}MB ";
        elseif (!in_array($this->File['type'], $typeFile)):
            $this->Result = false;
            $this->Error = "tipo de arquivo invalido";
        else:
            $this->createFolder($this->Folder);
            $this->checkFileName(1);
            $this->moveFile();
        endif;
    }

    /**     *
     * <b>uploadMidia</b>
     * Metódo resposanvel pelo upload de midias
     * @param array $file - recebe o arquivo
     * @param string $name - recebe o nome do arquivo
     * @param string $folder - pasta onde será armazendaa o arquivo. Default: file
     * @param int $maxSizeFile - tamanho do arquivo em MB. Default: 2MB
     */
    public function uploadMidia(array $file, $name = null, $folder = null, $maxSizeFile = null) {
        $this->File = $file;
        $this->Name = ($name ? $name : substr($file['name'], 0, strpos($file['name'], '.')));
        $this->Folder = ($folder ? $folder : 'midia/');
        $maxSizeFile = (int) $maxSizeFile ? $maxSizeFile : 100; //tamanho do aquivo em MB
        //difinindo os tipos de arquivos
        $typeFile = ['video/mp4', //mp4
            'audio/mpeg3', //mp3
        ];

        //aplicando validações
        if ($this->File['size'] > ($maxSizeFile * (1024 * 1024))):
            $this->Result = false;
            $this->Error = "Arquivo muito grande. Envie um arquivo com tamanho máximo de {$maxSizeFile}MB ";
        elseif (!in_array($this->File['type'], $typeFile)):
            $this->Result = false;
            $this->Error = "tipo de arquivo invalido";
        else:
            $this->createFolder($this->Folder);
            $this->checkFileName(1);
            $this->moveFile();
        endif;
    }

    /**     *
     * <b>getResult</b>
     * @return retorna um resultado
     */
    function getResult() {
        return $this->Result;
    }

    /**     *
     * <b>getResultThumb</b>
     * @return retorna um resultado
     */
    function getResultThumb() {
        return $this->ResultThumb;
    }

    /**     *
     * <b>getError</b>
     * @return retorna uma mensagem
     */
    function getError() {
        return $this->Error;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    //metodo responsavel em criar a pasta com subpastas
    private function createFolder($folder) {
        $folderYear = date('Y') . '/'; //cria a subpasta ano
        $folderMouth = date('m') . '/'; //cria a subpasta mes
        $folderDay = date('d') . '/';  //cia a subpasta dia
        $this->checkFolder("{$folder}"); //criar a pasta pricipal
        $this->checkFolder("{$folder}" . "{$folderYear}"); //criar a ano
        $this->checkFolder("{$folder}" . "{$folderYear}" . "{$folderMouth}"); //criar a pasta dia 
        $this->checkFolder("{$folder}" . "{$folderYear}" . "{$folderMouth}" . "{$folderDay}");
        $this->checkFolder("{$folder}" . "{$folderYear}" . "{$folderMouth}" . "{$folderDay}" . "{$this->FolderThumb}"); //criar a pasta tjumb
        $this->Send = "{$folder}" . "{$folderYear}" . "{$folderMouth}" . "{$folderDay}"; //caminho do arquivo
        $this->SendThumb = "{$folder}" . "{$folderYear}" . "{$folderMouth}" . "{$folderDay}" . "{$this->FolderThumb}"; //caminha da thumb
    }

    //metodo responsavel por cirar a pasta responsavel pelo armazenamento de midias
    private function checkFolder($folder) {
        if (!file_exists(self::$BaseDir . $folder) && !is_dir(self::$BaseDir . $folder)):
            mkdir(self::$BaseDir . $folder, 0777);
        endif;
    }

    /* metodo resposnsavel em verificar o arquivo e renomear caso existe um arquivo com o mesmo nome */

    private function checkFileName($op) {
        $fileName = AuxClass::Name($this->Name) . strchr($this->File['name'], '.');
        $fileNameThumb = 'thumb-' . $this->Name;
        switch ($op):
            case 1:
                if (file_exists(self::$BaseDir . $this->Send . $fileName)):
                    $fileName = AuxClass::Name($this->Name) . time() . strchr($this->File['name'], '.');
                endif;
                $this->Name = $fileName;
                break;
            case 2:
                if (file_exists(self::$BaseDir . $this->Send . $this->SendThumb . $fileNameThumb)):
                    $fileNameThumb = 'thumb-' . $this->Name . time();
                endif;
                $this->NameThumb = $fileNameThumb;
                break;
            default:
                echo 'opçao invalida! Escolha 1 ou 2';
        endswitch;
    }

    //metodo resposavel em executar o upload da imagem
    private function exeUploadImage($op) {
        switch ($this->File['type']):
            //obtendo o imagem do tipo jpg
            case 'image/jpeg':
            case 'image/jpg':
            case 'image/pjpeg':
                //Cria uma nova imagem a a partir de um arquivo ou URL
                $this->Image = imagecreatefromjpeg($this->File['tmp_name']);
                break;
            //obtendo o imagem do tipo png
            case 'image/png':
            case 'image/x-png':
                //Cria uma nova imagem a a partir de um arquivo ou URL
                $this->Image = imagecreatefrompng($this->File['tmp_name']);
                break;
            default :
        endswitch;

        //validando o tipo de imagem
        if (!$this->Image):
            $this->Result = false;
            $this->Error = '<b>Tipo de arquivo invalido!</b> envie um arquivo no formato JPG ou PNG';
        else:
            //capturando as dimensoes da imagem
            $w = imagesx($this->Image); //pegnado a largura da imagem
            $h = imagesy($this->Image); //pegado a altura da imagem
            $nw = ($this->ImgW < $w ? $this->ImgW : $w); //definindo uma nova largura
            $nh = ($nw * $h) / $w; //difininod uma nova altura proprocional
            $NewImage = imagecreatetruecolor($nw, $nh); //criando uma imagem com as novas dimensões
            imagesavealpha($NewImage, true); //ativando alpha para imagens com fundo trasnparentes;
            imagealphablending($NewImage, FALSE);
            imagecopyresampled($NewImage, $this->Image, 0, 0, 0, 0, $nw, $nh, $w, $h);

            //definindo dimensões da thumbnail
            $thumbW = $this->ImgWThumb; //definindo uma nova largura
            $thumbH = ($thumbW * $h) / $w; //difininod uma nova altura proprocional
            switch ($op):
                case 1:
                    switch ($this->File['type']):
                        //obtendo o imagem do tipo jpg
                        case 'image/jpeg':
                        case 'image/jpg':
                        case 'image/pjpeg':
                            //copiando imagem
                            imagejpeg($NewImage, self::$BaseDir . $this->Send . $this->Name);
                            break;
                        //obtendo o imagem do tipo png
                        case 'image/png':
                        case 'image/x-png':
                            //Cria uma nova imagem a a partir de um arquivo ou URL
                            imagepng($NewImage, self::$BaseDir . $this->Send . $this->Name);
                            break;
                    endswitch;
                    break;
                case 2:
                    $NewThumb = imagecreatetruecolor($thumbW, $thumbH);
                    imagesavealpha($NewThumb, true); //ativando alpha para imagens com fundo trasnparentes;
                    imagealphablending($NewThumb, true);
                    imagecopyresampled($NewThumb, $this->Image, 0, 0, 0, 0, $thumbW, $thumbH, $w, $h);
                    switch ($this->File['type']):

                        //obtendo o imagem do tipo jpg
                        case 'image/jpeg':
                        case 'image/jpg':
                        case 'image/pjpeg':
                            //copiando imagem
                            imagejpeg($NewThumb, self::$BaseDir . $this->SendThumb . $this->NameThumb);
                            break;
                        //obtendo o imagem do tipo png
                        case 'image/png':
                        case 'image/x-png':
                            //Cria uma nova imagem a a partir de um arquivo ou URL
                            imagejpeg($NewThumb, self::$BaseDir . $this->SendThumb . $this->NameThumb);
                            break;
                    endswitch;
                    break;
            endswitch;
            if (!$NewImage):
                $this->Result = false;
                $this->Error = '<b>Tipo de arquivo invalido!</b> envie um arquivo no formato JPG ou PNG';
            else:
                $this->Result = $this->Send . $this->Name;
                $this->ResultThumb = $this->SendThumb . $this->NameThumb;
                $this->Error = false;


            endif;
            imagedestroy($this->Image);
            imagedestroy($NewImage);
        endif;
    }

    //ENVIO DE ARQUIVO
    private function moveFile() {
        //verificando se o arquivo foi movido
        if (move_uploaded_file($this->File['tmp_name'], self::$BaseDir . $this->Send . $this->Name)):
            $this->Result = $this->Send . $this->Name;
            $this->Error = null;
        else:
            $this->Result = false;
            $this->Error = 'Erro ao mover o arquivo. Por favor, tente novamente ';
        endif;
    }

}
