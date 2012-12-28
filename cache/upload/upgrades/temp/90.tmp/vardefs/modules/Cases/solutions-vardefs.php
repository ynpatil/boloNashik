<?php
/*********************************************************************************
 * New field for Cases
 * pointing to associated solutions
 ********************************************************************************/

$dictionary['Case']['fields']['solutions'] = array (
		'name'         => 'solutions',
		'type'         => 'link',
		'relationship' => 'cases_solutions',
		'source'       =>'non-db',
);
?>

