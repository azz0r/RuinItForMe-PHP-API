<?php


class System_Map {


    public function __construct() {
        $this->map = array(
            'Spoiler' => array(
                'accepted'      => array('_id', 'id', 'title', 'description', 'email', 'category', 'updated'),
                'post_required' => array('title', 'description')
            ),
            'Category' => array(
                'accepted'      => array('_id', 'id', 'title', 'description', 'updated'),
                'post_required' => array('title', 'description')
            )
        );
    }


    public function validationErrors($key, $document, $type) {
        if (isset($this->map[$key]) && isset($this->map[$key]["{$type}_required"])) {
            $errors = array();
            foreach ($this->map[$key]["{$type}_required"] as $key) {
                if (!isset($document->{$key})) {
                    $errors[$key] = "must be set";
                } else if (empty($document->{$key})) {
                    $errors[$key] = "must not be empty";
                }
            }
            return $errors;
        }
        return false;
    }


    public function get($key, $document) {
        $options = array('key' => $key, 'remove_private' => true, 'no_missing_fields' => true, 'output_id' => true);
        return $this->returnObject($document, $options);
    }


    public function post($key, $document) {
        $options = array('key' => $key);
        return $this->returnObject($document, $options);
    }


    public function put($key, $document) {
        $options = array('key' => $key);
        return $this->returnObject($document, $options);
    }


    public function returnObject($document, $options=array()) {
        $document = (object) $document;

        # do we want to ensure every field is present ?
        if (isset($options['no_missing_fields'])) {
            $document = $this->noMissingFields($options['key'], $document);
        }

        # now look the object and ensure its all good
        foreach ($document as $key => $value) {
            if (!in_array($key, $this->map[$options['key']]['accepted'])) {
                unset($document->$key);
            }
            if (isset($options['remove_private']) && isset($this->map[$options['key']]['private']) && in_array($key, $this->map[$options['key']]['private'])) {
                unset($document->$key);
            }
        }

        if (isset($options['output_id'])) {
            $document = $this->outputId($document);
        }

        return $document;
    }


    private function outputId($document) {
        $document->id = $document->_id->{'$id'};
        return $document;
    }


    private function noMissingFields($key, $document) {
        if (isset($this->map[$key]['accepted'])) {
            foreach ($this->map[$key]['accepted'] as $field) {
                if (!isset($document->{$field})) {
                    $document->{$field} = false;
                }
            }
        }
        return $document;
    }


}