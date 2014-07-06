<?php

class dotDev_Form_Element_FileValue extends ArrayObject
{
    public function __toString()
    {
        $result = '';
        if(isset($this->name)) {
            $result = $this->name;
        }
        return $result;
    }
}
