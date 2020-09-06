<?php

namespace JavadGholipoor\Core\Scopes;

trait PublicScopes
{

    private $fields = [];

    public function scopeAdvanceSearch($query, $fields)
    {
        $this->fields = $fields;
        $q = $_REQUEST['q'] ?? '';
        if (!empty($q)) {
            $searchQueryParts = $this->searchQueryParts();
            if ($searchQueryParts['status']) {
                $query->where($searchQueryParts['field'], 'like', '%' . $searchQueryParts['value'] . '%');
            } else {
                $q = $this->clearSearchQuery($q);
                $like = " LIKE '%{$q}%'";
                $query->whereRaw(implode($like . " OR ", $fields) . $like);
            }
        }
    }

    public function scopeAdvanceSort($query)
    {
        $orderBy = $_REQUEST['orderBy'] ?? 'id';
        $ordering = $_REQUEST['ordering'] ?? 'desc';

        if (isset($_REQUEST['q'])) {
            $searchQueryParts = $this->searchQueryParts();
            if ($searchQueryParts['status']) {
                $orderBy = $searchQueryParts['field'];
            }
        }

        $query->orderBy($orderBy, $ordering);
    }

    public function clearSearchQuery($query)
    {
        $replaces = [
            '+' => ' ',
            '-' => ' ',
            '_' => ' ',
            '{' => '',
            '}' => '',
            '[' => '',
            ']' => '',
        ];
        if (isset($_REQUEST['postType'])) {
            $postType = getPostType($_REQUEST['postType']);
            if ($postType != null) {
                $replaces = array_merge($postType->replaces, $replaces);
            }
        } else {
           foreach (getPostTypes() as $postType) {
               $replaces = array_merge($postType->replaces, $replaces);
           }
        }

        foreach ($replaces as $k => $v)
            $query = str_replace($k, $v, $query);

        return $query;
    }

    public function searchQueryParts()
    {
        $field = null;
        $value = null;
        $searchByField = false;
        $q = $_REQUEST['q'] ?? '';
        foreach ($this->fields as $field) {
            $fieldParts = explode('.', $field);
            $fieldName = count($fieldParts) > 1 ? $fieldParts[1] : $fieldParts[0];
            if ("{$fieldName}-" == substr($q, 0, strlen(count($fieldParts) > 1 ? $fieldParts[1] : $fieldParts[0])+1)) {
                $searchByField = true;
                $field = $fieldName;
                $value = str_replace("{$fieldName}-", '', $q);
                break;
            }
        }
        return [
            'status' => $searchByField,
            'field' => $field,
            'value' => $value
        ];
    }

}
