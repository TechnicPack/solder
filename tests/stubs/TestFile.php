<?php
/*
 * This file is part of solder.
 *
 * (c) Kyle Klaus <kklaus@indemnity83.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use bheller\ImagesGenerator\ImagesGeneratorProvider;

class TestFile
{
    private $path;
    private $mimeType;
    private $size;
    private $transient;

    /**
     * Generate an image file.
     *
     * @param int $width
     * @param int $height
     * @param string $type
     *
     * @return static
     */
    public static function imageGenerator($width, $height, $type)
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new ImagesGeneratorProvider($faker));

        $image = $faker->imageGenerator(null, $width, $height, $type, true);

        return new static($image, true);
    }

    /**
     * Generate a text file.
     *
     * @param int $characters
     *
     * @return static
     */
    public static function textGenerator($characters)
    {
        $faker = Faker\Factory::create();
        $file = tempnam('/tmp', 'SolderFake');

        $handle = fopen($file, 'w');
        fwrite($handle, $faker->text($characters));
        fclose($handle);

        return new static($file, true);
    }

    /**
     * TestFile constructor.
     *
     * @param $path
     * @param int $size
     * @param string $mimeType
     * @param bool $transient
     */
    public function __construct($path, $transient = false, $size = null, $mimeType = null)
    {
        $this->size = $size ?: filesize($path);
        $this->mimeType = $mimeType ?: mime_content_type($path);
        $this->path = $path;
        $this->transient = $transient;
    }

    /**
     * @param mixed $size
     *
     * @return TestFile
     */
    public function overrideSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @param string $mimeType
     *
     * @return TestFile
     */
    public function overrideMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Get file contents as string.
     *
     * @return string
     */
    public function getContents()
    {
        return file_get_contents($this->path);
    }

    /**
     * Deconstruct the TestFile.
     *
     * If the TestFile was flagged as transient on construction then we need to
     * make sure we cleanup the file that was generated.
     */
    public function __destruct()
    {
        if ($this->transient) {
            unlink($this->getPath());
        }
    }
}
