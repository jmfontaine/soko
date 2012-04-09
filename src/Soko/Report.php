<?php
namespace Soko;

use Soko\Action\ActionInterface;

class Report
{
    private $data = array();

    public function addActionData(ActionInterface $action, $isSuccess, $output)
    {
        $this->data[] = array(
            'action'    => $action,
            'isSuccess' => $isSuccess,
            'output'    => $output,
        );
    }

    public function getData()
    {
        return $this->data;
    }
}
