<?php
if(empty($GLOBALS['sugarEntry'])) die('Not A Valid Entry Point');
/**
 * Subpanels layout definition for Solutions
 * ToDo:     must change popup (Account/Contact/...) in EditView of Note to include Solutions object
 */
 
$layout_defs['ProblemSolution'] = array(
    // list of what Subpanels to show in the DetailView 
    'subpanel_setup' => array(
        'history' => array(
            'order'                     => 10,
            'sort_order'                => 'desc',
            'sort_by'                   => 'date_modified',
            'title_key'                 => 'LBL_HISTORY_SUBPANEL_TITLE',
            'type'                      => 'collection',
            'subpanel_name'             => 'history',   //this values is not associated with a physical file.
            'module'                    => 'Activities',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopCreateNoteButton'),
                array('widget_class' => 'SubPanelTopArchiveEmailButton'),
//                array('widget_class' => 'SubPanelTopSummaryButton'),
            ),
            'collection_list' => array( 
                'notes' => array(
                    'module'            => 'Notes',
                    'subpanel_name'     => 'ForHistory',
                    'get_subpanel_data' => 'notes',
                ),  
                'emails' => array(
                    'module'            => 'Emails',
                    'subpanel_name'     => 'ForHistory',
                    'get_subpanel_data' => 'emails',
                ),  
            )           
        ),

  'solvedcases'           => array(
   'top_buttons'        => array( array('widget_class' => 'SubPanelTopSelectButton', 
                                        'popup_module' => 'Cases'), ),
   'order'              => 20,
   'module'             => 'Cases',
   'subpanel_name'      => 'default',
   'get_subpanel_data'  => 'cases',
   'add_subpanel_data'  => 'case_id',
   'title_key'          => 'LBL_CASES_SUBPANEL_TITLE',
  ),

    ),
);
?>
