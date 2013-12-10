<?php

namespace Konkurencia\CommonBundle\Api;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Templating\Helper as VichHelper;
use Liip\ImagineBundle\Templating\Helper as LiipHelper;

/**
 * class ApiBuilder
 *
 * Responsible for building API resources
 * Builds resources from loaded configuration
 */
class ApiBuilder {

    /**
     * @var array
     *
     * Array of resources mapped from config
     */
    private $resources;

    /**
     * @var string
     *
     * Base url for uri building
     */
    private $baseUrl;

    /**
     * @var VichHelper
     */
    private $vichHelper;

    /**
     * @var LiipHelper
     */
    private $liipHelper;

    public function __construct($resources, $baseUrl, $vichHelper = null, $liipHelper = null)
    {
        $this->resources = $resources;
        $this->baseUrl = $baseUrl;
        $this->vichHelper = $vichHelper;
        $this->liipHelper = $liipHelper;
    }

    /**
     * Builds list resources for given resource
     *
     * @param $results
     * @param $resourceName
     * @return array
     */
    public function buildListResources($results, $resourceName) {
        $resourceConfig = $this->getResourceConfig($resourceName);

        return array($resourceName => $this->buildResources($results, $resourceConfig, $resourceName));
    }

    /**
     * Builds detail resource(s) for given resource
     *
     * @param $results
     * @param $resourceName
     * @return array
     */
    public function buildDetailResources($results, $resourceName) {
        $resourceConfig = $this->getResourceConfig($resourceName, true);
        if(empty($results[0])) return array();

        $resources = $this->buildResources($results, $resourceConfig, $resourceName);
        return $resources[0];
    }

    /**
     * Builds a resource set by it's given configured mapping on given set
     *
     * @param $results
     * @param $resourceConfig
     * @param $resourceName
     * @return array
     */
    private function buildResources($results, $resourceConfig, $resourceName) {
        //combines array from config keys and values
        $mapping = array_combine($resourceConfig['keys'], $resourceConfig['values']);
        $mappedResults = array();
        $foreigns = (array_key_exists('foreigns', $resourceConfig) ? $resourceConfig['foreigns'] : array());
        //loops on result
        foreach ($results as $i => $entity) {
            if (!empty($mapping)) {
                $mappedResults[$i] = array();
            }
            $entityArray = $this->entityToArray($entity);
            //loops on config mapping
            foreach ($mapping as $apiField => $entityField) {
                //handle special field - URL
                if ('url' === $apiField) {
                    $mappedResults[$i][$apiField] = $this->baseUrl . $resourceName . '/' . $entityArray[$entityField];
                } elseif ($entityArray[$entityField] instanceof File) { //handle images
                    $mappedResults[$i][$apiField] = $this->liipHelper->filter($this->vichHelper->asset($entity, $entityField), $entityField);
                }
                else {
                    $mappedResults[$i][$apiField] = $entityArray[$entityField];
                }
            }

            //handle foreign resrouces
            if (!empty($foreigns)) {
                foreach ($foreigns as $sectionTitle => $foreignResourceConfig) {
                    $getter = "get" . ucfirst($foreignResourceConfig['resource']);
                    $foreignResources = $entity->$getter();
                    $resourceNameParam = ($foreignResourceConfig['resourceUrl']) ? $foreignResourceConfig['resourceUrl'] : $foreignResourceConfig['resource'];
                    $builtForeigns = $this->buildResources($foreignResources, $foreignResourceConfig['field_mapping'], $resourceNameParam);
                    if ($foreignResourceConfig['isList'] || empty($builtForeigns)) {
                        $mappedResults[$i][$sectionTitle] = $builtForeigns;
                    }
                    /*
                     * https://github.com/teo-sk/konkurencia/issues/9
                     * http://stackoverflow.com/a/265144/777436
                     */
                    elseif(array_values($builtForeigns[0]) === $builtForeigns[0]) {
                        $mappedResults[$i][$sectionTitle] = $builtForeigns[0][0];
                    }
                    else {
                        $mappedResults[$i][$sectionTitle] = $builtForeigns[0];
                    }
                }
            }
        }

        return $mappedResults;
    }

    /**
     * Return correct configuration part from all resrouce configs
     *
     * @param $resourceName
     * @param bool $isDetail
     * @return mixed
     * @throws \Exception
     */
    private function getResourceConfig($resourceName, $isDetail = false) {
        if (!array_key_exists($resourceName, $this->resources)) {
            throw new \Exception("Configuration for given resource does not exist.");
        }
        return ($isDetail) ? $this->resources[$resourceName]['detail_field_mapping']
                           : $this->resources[$resourceName]['list_field_mapping']
            ;
    }

    /**
     * Converts an entity to array using PropertyPath class
     *
     * @param $entity
     * @return array
     */
    private function entityToArray($entity) {
        $reflectedClass = new \ReflectionClass($entity);
        $objectProperties = $reflectedClass->getProperties();
        $data = array();
        foreach ($objectProperties as $objectProperty) {
            $property = $objectProperty->getName();

            $data[$property] = $entity->__get($property);
        }

        return $data;
    }


}