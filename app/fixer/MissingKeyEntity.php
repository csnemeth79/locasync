<?php

class MissingKeyEntity
{
    private $file;
    private $missed;

    public function MissingKeyEntity($file)
    {
        $this->file = $file;
    }

    public function add($key, $value)
    {
        if($this->missed == null) {
            $this->missed = array();
        }

        $this->missed[$key] = $value;
    }

    public function isEmpty()
    {
        return $this->missed == null;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $missed
     */
    public function setMissed($missed)
    {
        $this->missed = $missed;
    }

    /**
     * @return mixed
     */
    public function getMissed()
    {
        return $this->missed;
    }


}