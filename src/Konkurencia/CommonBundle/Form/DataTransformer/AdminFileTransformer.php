<?php

namespace Konkurencia\CommonBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;



class AdminFileTransformer implements DataTransformerInterface {

    /**
     * @param null|File $name
     * @return null
     */
    public function transform($name)
    {

        return null;
    }

    /**
     * @param UploadedFile $data
     * @return UploadedFile
     */
    public function reverseTransform($data)
    {

        return $data;
    }
}