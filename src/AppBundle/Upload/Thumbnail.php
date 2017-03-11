<?php
namespace AppBundle\Upload;

use Symfony\Component\HttpFoundation\File\File;

class Thumbnail
{
    protected $originalBuffer = null;
    protected $thumbnailBuffer = null;
    protected $width = 0;
    protected $height = 0;

    public function __construct(File $file)
    {
        $data = getimagesize($file->getRealPath());
        if (!$data) {
            throw new Exception\ImageReadException();
        }

        switch ($data[2]) {
            case IMAGETYPE_JPEG:
            case IMAGETYPE_JPEG2000:
                $this->originalBuffer = imagecreatefromjpeg($file->getRealPath());
                break;

            case IMAGETYPE_PNG:
                $this->originalBuffer = imagecreatefrompng($file->getRealPath());
                break;

            default:
                throw new Exception\UnsupportedFileFormatException();
        }

        if (!$this->originalBuffer) {
            throw new Exception\ImageReadException();
        }

        $this->width = imagesx($this->originalBuffer);
        $this->height = imagesy($this->originalBuffer);
    }

    public function __destruct()
    {
        if (is_resource($this->originalBuffer)) {
            imagedestroy($this->originalBuffer);
        }
        if (is_resource($this->thumbnailBuffer)) {
            imagedestroy($this->thumbnailBuffer);
        }
    }

    public function getOriginalWidth(): int
    {
        return $this->width;
    }

    public function getOriginalHeight(): int
    {
        return $this->height;
    }

    /**
     * Tworzy miniaturkę wyśrodkowaną i przyciętą do zadanych wymiarów.
     * @param string $directory Katalog, w którym zostanie zapisany plik docelowy.
     * @param int $width Szerokość miniaturki.
     * @param int $height Wysokość miniaturki.
     * @param int $quality Jakość wyjściowego pliku JPEG (od 0 do 100).
     * @return File Plik wynikowy.
     */
    public function createCroppedThumbnail(string $directory, int $width, int $height, int $quality): File
    {
        $ratioIn = $this->width / $this->height;
        $ratioOut = $width / $height;

        if ($ratioIn > $ratioOut) {
            $sourceWidth = (int) floor($this->height * $ratioOut);
            $sourceHeight = $this->height;
            $sourceX = (int) floor(($this->width - $sourceWidth) / 2);
            $sourceY = 0;
        } else {
            $sourceHeight = (int) floor($this->width / $ratioOut);
            $sourceWidth = $this->width;
            $sourceX = 0;
            $sourceY = 0;
        }

        return $this->createThumbnail($directory, $width, $height, $sourceX, $sourceY, $sourceWidth, $sourceHeight, $quality);
    }

    protected function createThumbnail(
        string $directory,
        int $width,
        int $height,
        int $sourceX,
        int $sourceY,
        int $sourceWidth,
        int $sourceHeight,
        int $quality
    ): File {
        $this->thumbnailBuffer = imagecreatetruecolor($width, $height);

        imagecopyresampled(
            $this->thumbnailBuffer,
            $this->originalBuffer,
            0,
            0,
            $sourceX,
            $sourceY,
            $width,
            $height,
            $sourceWidth,
            $sourceHeight
        );

        ob_start();
        imagejpeg($this->thumbnailBuffer, null, $quality);
        $data = ob_get_clean();
        imagedestroy($this->thumbnailBuffer);

        $hash = substr(sha1($data), 0, 10);
        $path = $directory . DIRECTORY_SEPARATOR . $hash . '.jpg';
        file_put_contents($path, $data);

        return new File($path);
    }
}
