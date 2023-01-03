<?php
include 'EvaluatorsHandler.php';
include 'LangEvaluator.php';

$results = (new EvaluatorsHandler())->getEvaluatorOutput($_POST);

echo json_encode($results);
