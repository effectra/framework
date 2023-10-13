<?php

declare(strict_types=1);

namespace Effectra\Core;

use Effectra\Core\Exceptions\UploadException;
use Effectra\Core\Upload\AppUpload;
use Effectra\Fs\Directory;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Class Upload
 *
 * Handles file uploads using PSR-7 UploadedFileInterface objects.
 */
class Upload
{
    /**
     * @var UploadedFileInterface[] $files An array of UploadedFileInterface objects.
     */
    protected array $files;

    /**
     * @var string $prename The prefix name to be added to the file name during upload.
     */
    protected string $prename = '';

    /**
     * @var bool $randomName Whether to generate a random name for the uploaded file.
     */
    protected bool $randomName = false;

    /**
     * @var array $state An array that holds the upload state information for each file.
     */
    protected array $state = [];

    /**
     * Upload constructor.
     *
     * @param UploadedFileInterface|array $files An UploadedFileInterface object or an array of such objects.
     */
    public function __construct(UploadedFileInterface|array $files)
    {

        if (is_array($files)) {
            foreach ($files as $file) {
                $this->validateFile($file);
            }
            $this->files = $files;
        } else {
            $this->validateFile($files);
            $this->files[] = $files;
        }
    }

    /**
     * Validates if the given file is an instance of UploadedFileInterface.
     *
     * @param UploadedFileInterface $file The file to validate.
     * @throws UploadException If the file is not an instance of UploadedFileInterface.
     */
    public function validateFile($file): void
    {
        if (!$file instanceof UploadedFileInterface) {
            throw new UploadException("File must be instance of Psr\Http\Message\UploadedFileInterface");
        }
    }

    /**
     * Limits the number of files in the upload.
     *
     * @param int $number The maximum number of files to keep.
     */
    public function limitFiles(int $number): void
    {
        for ($i = 0; $i < $number; $i++) {
            $this->files = $this->files[$i];
        }
    }

    /**
     * Sets the prefix name to be added to the file name during upload.
     *
     * @param string $name The prefix name.
     */
    public function prename(string $name): void
    {
        $this->prename = $name;
    }

    /**
     * Sets the option to generate a random name for the uploaded file.
     */
    public function randomName(): void
    {
        $this->randomName = true;
    }

    /**
     * Saves the uploaded files to the specified path or the default path if not provided.
     *
     * @param string|null $path The path to save the files. Defaults to the default upload directory.
     * @throws UploadException If no files are uploaded or if there's an issue with creating directories.
     */
    public function save(?string $path = null): void
    {
        if (count($this->files) == 0) {
            throw new UploadException("No Files Uploaded");
        }
        if (!$path) {
            $path = AppUpload::getDriver()?->dir;
        }

        $this->uploadDir($path);

        foreach ($this->files as $key => $file) {

            if (!in_array(File::extension($file->getClientFilename()), AppUpload::getTypes())) {
                throw new UploadException("This Format Type '" . File::extension($file->getClientFilename()) . "' not allowed");
            }

            if ($file->getSize() > AppUpload::getMaxSize()) {
                throw new UploadException("Max uploading size is " . AppUpload::getMaxSize() . ", you file size is " . $file->getSize());
            }

            $fileName =  $this->prename . $file->getClientFilename();

            if ($this->randomName) {
                $fileName = $this->randomFileName($fileName);
            }

            $distention = $path . Path::ds() . $fileName;

            $file->moveTo($distention);

            $isMoved = method_exists($file, 'isMoved') ? $file?->isMoved() : File::exists($distention);

            $this->state[$key] = [
                'input'          => $key,
                'file_name'      => $fileName,
                'uploaded'       => $isMoved,
                'file_path'      => $distention,
                'file_url'      => $_ENV['APP_URL'] .'/'. str_replace(Application::publicPath(),'',$distention),
                'extension'      => File::exists($distention) ? File::extension($distention) : null,
                'media_type'     => $file->getClientMediaType(),
                'size'           => $file->getSize(),
                'size_format'    => $this->formatBytes($file->getSize()),
                'error'          => $this->error($file->getError()),
            ];
        }
    }

    /**
     * Returns the upload state information for the uploaded files.
     *
     * @param string|null $key The specific key for which to retrieve the state.
     * @return array The upload state information for the specified key or all files if no key is provided.
     */
    public function result(?string $key = null): array
    {
        return $key ? $this->state[$key] : $this->state;
    }

    /**
     * Creates the upload directory if it does not exist.
     *
     * @param string $path The path of the upload directory.
     * @throws UploadException If directory creation fails.
     */
    private function uploadDir(string $path): void
    {
        if (!Directory::isDirectory($path)) {
            $result = Directory::make($path);
            if ($result === false) {
                throw new UploadException("Failed making directory at: $path");
            }
        }
    }

    /**
     * Generates a random file name for the uploaded file.
     *
     * @param string $fileName The original file name.
     * @return string The random file name.
     */
    private function randomFileName(string $fileName): string
    {
        return time() . '-' . uniqid() . '.' . File::extension($fileName);
    }

    /**
     * Converts the PHP upload error code to an error message.
     *
     * @param int $error_number The PHP upload error code.
     * @return string The corresponding error message.
     */
    public static function error(int $error_number): string
    {
        /** @var array */
        $phpFileUploadErrors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        return $phpFileUploadErrors[$error_number];
    }

    /**
     * Formats the file size in bytes to a human-readable format.
     *
     * @param int $size The file size in bytes.
     * @param int $precision The number of decimal places for the size.
     * @return string The formatted file size.
     */
    private function formatBytes(int $size, int $precision = 2): string
    {
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)].'B';
    }

}
