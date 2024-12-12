<?php

/**
 * Class that handles the abstraction of a HTTP Request to a REST web service.
 *
 * @author Marcos Mercedes <marcos.mercedesn@gmail.com>
 * @package TRest\Http
 */
namespace TRest\Http;

class Request extends RequestProperties {

    /**
     * A hashed url used as an unique key in order to be able to use caching for
     * the responses
     *
     * @return string
     */
    public function getUrlHash() {
        return md5($this->buildUrl() . '?' . implode('&', $this->getParameters()));
    }

    /**
     *
     * @param boolean $addParameters
     *            decides if the url parameters should be added to the url
     *            returned
     * @return string
     */
    public function buildUrl($addParameters = true, $addEntity = true) {
        $array = array(
            rtrim($this->getUrl(), '/'),
            $this->getResource()
        );
        if ($this->getPath())
            $array[] = $this->getPath();
        if ($addEntity && $this->getEntity())
            $array[] = $this->getEntity();
        if ($addParameters) {
            if (count($this->getParameters())) {
                $array[] = '?' . $this->getFormattedHttpParameters();
            }
            array_walk($array, function (&$item, $key) {
                $item = rtrim($item, '/');
            });
        }
        return implode('/', $array);
    }

    private function getFormattedHttpParameters(){
        $params = array();
        $parameters = $this->getParameters();
        foreach($parameters as $key => $values) {
            $params[] = "$key=$values";
        }
        return implode( '&', $params );
    }
}
