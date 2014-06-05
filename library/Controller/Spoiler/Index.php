<?php


class Controller_Spoiler_Index extends System_Controller {


    const tableName = 'Spoiler';


    public function init() {

        $this->id              = $this->_getId ();
        $this->count           = (bool) $this->_getParam ('count', false);
        $this->tableCollection = $this->db->{self::tableName};
    }


    public function get() {

        $this->getPreDispatch ();

        if ($this->count) {
            return array('count' => $this->collection);

        } else if (!empty ($this->id) && empty($this->collection)) {
            $this->setError (404, array('id' => $this->id));

        } else {
            $this->getPostDispatch ();
            return $this->results;
        }
    }


    private function getPreDispatch() {

        # get the default filters: from, to, order_by, order_direction
        $this->_getQueryFilters ();

        # get custom filters for this controller
        $this->getFilters ();

        if ($this->count) {
            $this->collection = $this->tableCollection->count ($this->filters->items);

        } else if (empty ($this->id)) {
            $this->collection = $this->tableCollection
                ->find ($this->filters->items)
                ->limit ($this->filters->to)
                ->skip ($this->filters->from)
                ->sort (
                    array($this->filters->orderBy => ($this->filters->orderDirection == 'ASC' ? 1 : -1))
                );
        } else {
            $this->collection = $this->tableCollection->findOne (array('_id' => new MongoId($this->id)));
        }
    }


    # create the filters for the GET request
    private function getFilters() {

        # preset the default items array so were php 5 strict
        $this->filters->items = array();
        $this->filters->items['deleted'] = false;
    }


    # process our collection and do any clean up required
    # our mapping system will turn any fields declared into the right type

    private function getPostDispatch() {

        if (!empty($this->collection)) {
            $this->collection = !empty ($this->id) ? array($this->collection) : $this->collection;
            foreach ($this->collection as $document) {
                $document = $this->map->get (self::tableName, $document);
                $document->description = preg_replace('/<br \/>/iU', "\r\n"  , $document->description);
                $this->results[] = $document;
            }
            $this->results = !empty($this->id) ? $this->results[0] : $this->results;
        }
    }


    # create a new document
    public function post() {

        try {
            # only internal users can add a user
            $document = $this->map->post('Spoiler', $this->vars);
            $document->deleted = false;
            $document->created = $document->updated = new MongoDate();
            $document->description = nl2br($document->description);

            if ($validationErrors = $this->map->validationErrors('Spoiler', $document, 'post')) {
                return $this->setError (205, $validationErrors);
            } else {
                if ($this->tableCollection->insert ($document)) {
                    return $this->map->get('Spoiler', $document);
                } else {
                    return $this->setError (210);
                }
            }

        } catch (Exception $e) {
            $this->setError (230, array('thrown' => ($e->getMessage ()), 'sent' => $this->vars));

        } catch (MongoCursorException $e) {
            $this->setError (240, serialize (array_merge ($this->vars, array($e->getMessage ()))));
        }
    }


    # update a document
    public function put() {

        return false;
    }


    # delete an document
    public function delete() {

        return false;
    }


}