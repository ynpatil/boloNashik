<?PHP
	global $unzip_dir;
	$path_parts = pathinfo($_SERVER["SCRIPT_FILENAME"]);
	$path_name=$path_parts['dirname'];

	$TeamsOS_File = $path_name."/modules/TeamsOS/TeamOS.php";

	if(file_exists($TeamsOS_File)) {
		$manifest = array(
			'acceptable_sugar_versions' => array(
				'regex_matches' => array(
					0 => "4\.5\.[01].*"
				),
			),
			'acceptable_sugar_flavors' => array (
				0 => 'OS',
			),
			'name'             => 'TeamsOS',
			'description'      => 'Teams Open Source',
			'is_uninstallable' => true,
			'author'           => 'Lampada CRM,<br>CRMUpgrades.com',
			'published_date'   => '2006/01/07',
			'version'          => '3.0d (upgrade)',
			'type'             => 'module',
			'icon'             => 'include/images/LampadaCRM.png',
		);

		$installdefs = array(
			'id'   => 'TeamsOS',
			'copy' => array(
				array(
					'from' => '<basepath>/newfiles/TeamsOS/TeamFormBase.php',
					'to'   => 'modules/TeamsOS/TeamFormBase.php'
				),
				array(
					'from' => '<basepath>/newfiles/TeamsOS/TeamOS.php',
					'to'   => 'modules/TeamsOS/TeamOS.php'
				),
				array(
				    'from'=> '<basepath>/newfiles/orgchart.php',
					'to'=> 'orgchart.php',
				),
				array(
					'from' => '<basepath>/patch/modules/Users/EditView.html',
					'to'   => 'modules/Users/EditView.html'
				),
			),

			//Custom Menu files.  Not exactly what this file was designed for
			//but the menu.php file is one of the most useful files there are
			'menu'=> array(
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Accounts'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Documents'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Contacts'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Bugs'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Calls'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Campaigns'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Cases'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Employees'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Leads'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Meetings'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Notes'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Opportunities'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Project'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'ProjectTask'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Prospects'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Tasks'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Users'
				),
			),

			//Language files should be done this way never replace the whole language file.
			'language'=> array (
				array(
					'from'=> '<basepath>/language/modules/application/app_strings.php',
					'to_module'=> 'application',
					'language'=>'en_us'
				),
				array(
					'from'=> '<basepath>/language/modules/application/es_es_app_strings.php',
					'to_module'=> 'application',
					'language'=>'es_es'
				),
				array(
					'from'=> '<basepath>/language/modules/application/ge_ge_app_strings.php',
					'to_module'=> 'application',
					'language'=>'ge_ge'
				),
				array(
					'from'=> '<basepath>/language/modules/application/fr_FR_app_strings.php',
					'to_module'=> 'application',
					'language'=>'fr_FR'
				),
				array(
					'from'=> '<basepath>/language/modules/application/pt_br_app_strings.php',
					'to_module'=> 'application',
					'language'=>'pt_br'
				),
				array(
					'from'=> '<basepath>/language/modules/application/ru_ru_app_strings.php',
					'to_module'=> 'application',
					'language'=>'ru_ru'
				),


				array(
					'from'=> '<basepath>/language/modules/Administration/en_us.admin.php',
					'to_module'=> 'Administration',
					'language'=>'en_us'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/es_es.admin.php',
					'to_module'=> 'Administration',
					'language'=>'es_es'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/fr_FR.admin.php',
					'to_module'=> 'Administration',
					'language'=>'fr_FR'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/ge_ge.admin.php',
					'to_module'=> 'Administration',
					'language'=>'ge_ge'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/pt_br.admin.php',
					'to_module'=> 'Administration',
					'language'=>'pt_br'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/ru_ru.admin.php',
					'to_module'=> 'Administration',
					'language'=>'ru_ru'
				),

				array(
					'from'=> '<basepath>/language/modules/Users/en_us.users.php',
					'to_module'=> 'Users',
					'language'=>'en_us'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/es_es.users.php',
					'to_module'=> 'Users',
					'language'=>'es_es'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/fr_FR.users.php',
					'to_module'=> 'Users',
					'language'=>'fr_FR'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/ge_ge.users.php',
					'to_module'=> 'Users',
					'language'=>'ge_ge'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/pt_br.users.php',
					'to_module'=> 'Users',
					'language'=>'pt_br'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/ru_ru.users.php',
					'to_module'=> 'Users',
					'language'=>'ru_ru'
				),

				//orgchart addon
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_en_gb.php',
					  'to_module'=> 'Accounts',
					  'language'=>'en_gb'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_en_gb.php',
					  'to_module'=> 'Contacts',
					  'language'=>'en_gb'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_en_gb.php',
					  'to_module'=> 'Employees',
					  'language'=>'en_gb'
				),
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_en_us.php',
					  'to_module'=> 'Accounts',
					  'language'=>'en_us'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_en_us.php',
					  'to_module'=> 'Contacts',
					  'language'=>'en_us'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_en_us.php',
					  'to_module'=> 'Employees',
					  'language'=>'en_us'
				),
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_ge_ge.php',
					  'to_module'=> 'Accounts',
					  'language'=>'ge_ge'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_ge_ge.php',
					  'to_module'=> 'Contacts',
					  'language'=>'ge_ge'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_ge_ge.php',
					  'to_module'=> 'Employees',
					  'language'=>'ge_ge'
				),
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_fr_FR.php',
					  'to_module'=> 'Accounts',
					  'language'=>'fr_FR'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_fr_FR.php',
					  'to_module'=> 'Contacts',
					  'language'=>'fr_FR'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_fr_FR.php',
					  'to_module'=> 'Employees',
					  'language'=>'fr_FR'
				),
			)
		);
	} else {
		$manifest = array(
			'acceptable_sugar_versions' => array(
				'regex_matches' => array(
					0 => "4\.5\.[01].*"
				),
			),
			'acceptable_sugar_flavors' => array (
				0 => 'OS',
			),
			'name'             => 'TeamsOS',
			'description'      => 'Teams Open Source',
			'is_uninstallable' => true,
			'author'           => 'Lampada CRM,<br>CRMUpgrades.com',
			'published_date'   => '2006/01/07',
			'version'          => '3.0d',
			'type'             => 'module',
			'icon'             => 'include/images/LampadaCRM.png',
		);

		$installdefs = array(
			'id'   => 'TeamsOS',
			'copy' => array(
				array(
					'from' => '<basepath>/include/images/LampadaCRM.png',
					'to'   => 'include/images/LampadaCRM.png'
				),
				array(
					'from' => '<basepath>/newfiles/TeamsOS',
					'to'   => 'modules/TeamsOS'
				),
				array(
					'from' => '<basepath>/patch/include/ListView/ListView.php',
					'to'   => 'include/ListView/ListView.php'
				),
				array(
					'from' => '<basepath>/patch/include/ListView/ListViewDisplay.php',
					'to'   => 'include/ListView/ListViewDisplay.php'
				),

				array(
					'from' => '<basepath>/patch/modules/Charts/code/Chart_lead_source_by_outcome.php',
					'to'   => 'modules/Charts/code/Chart_lead_source_by_outcome.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Charts/code/Chart_my_pipeline_by_sales_stage.php',
					'to'   => 'modules/Charts/code/Chart_my_pipeline_by_sales_stage.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Charts/code/Chart_outcome_by_month.php',
					'to'   => 'modules/Charts/code/Chart_outcome_by_month.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Charts/code/Chart_pipeline_by_lead_source.php',
					'to'   => 'modules/Charts/code/Chart_pipeline_by_lead_source.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Charts/code/Chart_pipeline_by_sales_stage.php',
					'to'   => 'modules/Charts/code/Chart_pipeline_by_sales_stage.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Calendar/templates/templates_calendar.php',
					'to'   => 'modules/Calendar/templates/templates_calendar.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Accounts/DetailView.html',
					'to'   => 'modules/Accounts/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Accounts/AccountFormBase.php',
					'to'   => 'modules/Accounts/AccountFormBase.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Accounts/EditView.html',
					'to'   => 'modules/Accounts/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Documents/DetailView.html',
					'to'   => 'modules/Documents/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Documents/EditView.html',
					'to'   => 'modules/Documents/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Bugs/DetailView.html',
					'to'   => 'modules/Bugs/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Bugs/Forms.php',
					'to'   => 'modules/Bugs/Forms.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Bugs/EditView.html',
					'to'   => 'modules/Bugs/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Calls/DetailView.html',
					'to'   => 'modules/Calls/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Calls/CallFormBase.php',
					'to'   => 'modules/Calls/CallFormBase.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Calls/EditView.html',
					'to'   => 'modules/Calls/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Campaigns/Forms.php',
					'to'   => 'modules/Campaigns/Forms.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Campaigns/Forms.html',
					'to'   => 'modules/Campaigns/Forms.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Campaigns/DetailView.html',
					'to'   => 'modules/Campaigns/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Campaigns/EditView.html',
					'to'   => 'modules/Campaigns/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Cases/DetailView.html',
					'to'   => 'modules/Cases/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Cases/EditView.html',
					'to'   => 'modules/Cases/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Cases/Forms.php',
					'to'   => 'modules/Cases/Forms.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Contacts/DetailView.html',
					'to'   => 'modules/Contacts/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Contacts/ContactFormBase.php',
					'to'   => 'modules/Contacts/ContactFormBase.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Contacts/EditView.html',
					'to'   => 'modules/Contacts/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Leads/DetailView.html',
					'to'   => 'modules/Leads/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Leads/EditView.html',
					'to'   => 'modules/Leads/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Leads/LeadFormBase.php',
					'to'   => 'modules/Leads/LeadFormBase.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Meetings/DetailView.html',
					'to'   => 'modules/Meetings/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Meetings/EditView.php',
					'to'   => 'modules/Meetings/EditView.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Meetings/EditView.html',
					'to'   => 'modules/Meetings/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Opportunities/DetailView.html',
					'to'   => 'modules/Opportunities/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Opportunities/OpportunityFormBase.php',
					'to'   => 'modules/Opportunities/OpportunityFormBase.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Opportunities/EditView.html',
					'to'   => 'modules/Opportunities/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Notes/DetailView.html',
					'to'   => 'modules/Notes/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Notes/EditView.html',
					'to'   => 'modules/Notes/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Project/DetailView.html',
					'to'   => 'modules/Project/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Project/EditView.html',
					'to'   => 'modules/Project/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Project/Forms.html',
					'to'   => 'modules/Project/Forms.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Project/Forms.php',
					'to'   => 'modules/Project/Forms.php'
				),
				array(
					'from' => '<basepath>/patch/modules/ProjectTask/DetailView.html',
					'to'   => 'modules/ProjectTask/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/ProjectTask/EditView.html',
					'to'   => 'modules/ProjectTask/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/ProjectTask/Forms.html',
					'to'   => 'modules/ProjectTask/Forms.html'
				),
				array(
					'from' => '<basepath>/patch/modules/ProjectTask/Forms.php',
					'to'   => 'modules/ProjectTask/Forms.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Tasks/DetailView.html',
					'to'   => 'modules/Tasks/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Tasks/EditView.html',
					'to'   => 'modules/Tasks/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Prospects/DetailView.html',
					'to'   => 'modules/Prospects/DetailView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Prospects/EditView.html',
					'to'   => 'modules/Prospects/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Users/EditView.html',
					'to'   => 'modules/Users/EditView.html'
				),
				array(
					'from' => '<basepath>/patch/modules/Users/EditView.php',
					'to'   => 'modules/Users/EditView.php'
				),
				array(
					'from' => '<basepath>/patch/modules/Users/DetailView.html',
					'to'   => 'modules/Users/DetailView.html'
				),

				//org chart add on
				array('from'=> '<basepath>/newfiles/orgchart.php',
					  'to'=> 'orgchart.php',
				),
				array('from'=> '<basepath>/newfiles/include/images/orgline.png',
					  'to'=> 'include/images/orgline.png',
				),
			),

			//Custom Menu files.  Not exactly what this file was designed for
			//but the menu.php file is one of the most useful files there are
			'menu'=> array(
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Accounts'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Documents'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Contacts'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Bugs'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Calls'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Campaigns'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Cases'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Employees'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Leads'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Meetings'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Notes'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Opportunities'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Project'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'ProjectTask'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Prospects'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Tasks'
				),
				array('from'=> '<basepath>/newfiles/TeamsOS_menu_code.php',
					  'to_module'=> 'Users'
				),
			),

			//Admin menus are done here so you don't have to touch index.php anymore
			'administration'=> array(
				array(
					'from'=>'<basepath>/patch/modules/Administration/teamsadminoption.php',
					'to' => 'modules/Administration/teamsadminoption.php',
				),
			),

			//Language files should be done this way never replace the whole language file.
			'language'=> array (
				array(
					'from'=> '<basepath>/language/modules/application/app_strings.php',
					'to_module'=> 'application',
					'language'=>'en_us'
				),
				array(
					'from'=> '<basepath>/language/modules/application/es_es_app_strings.php',
					'to_module'=> 'application',
					'language'=>'es_es'
				),
				array(
					'from'=> '<basepath>/language/modules/application/ge_ge_app_strings.php',
					'to_module'=> 'application',
					'language'=>'ge_ge'
				),
				array(
					'from'=> '<basepath>/language/modules/application/fr_FR_app_strings.php',
					'to_module'=> 'application',
					'language'=>'fr_FR'
				),
				array(
					'from'=> '<basepath>/language/modules/application/pt_br_app_strings.php',
					'to_module'=> 'application',
					'language'=>'pt_br'
				),
				array(
					'from'=> '<basepath>/language/modules/application/ru_ru_app_strings.php',
					'to_module'=> 'application',
					'language'=>'ru_ru'
				),


				array(
					'from'=> '<basepath>/language/modules/Administration/en_us.admin.php',
					'to_module'=> 'Administration',
					'language'=>'en_us'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/es_es.admin.php',
					'to_module'=> 'Administration',
					'language'=>'es_es'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/fr_FR.admin.php',
					'to_module'=> 'Administration',
					'language'=>'fr_FR'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/ge_ge.admin.php',
					'to_module'=> 'Administration',
					'language'=>'ge_ge'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/pt_br.admin.php',
					'to_module'=> 'Administration',
					'language'=>'pt_br'
				),
				array(
					'from'=> '<basepath>/language/modules/Administration/ru_ru.admin.php',
					'to_module'=> 'Administration',
					'language'=>'ru_ru'
				),

				array(
					'from'=> '<basepath>/language/modules/Users/en_us.users.php',
					'to_module'=> 'Users',
					'language'=>'en_us'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/es_es.users.php',
					'to_module'=> 'Users',
					'language'=>'es_es'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/fr_FR.users.php',
					'to_module'=> 'Users',
					'language'=>'fr_FR'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/ge_ge.users.php',
					'to_module'=> 'Users',
					'language'=>'ge_ge'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/pt_br.users.php',
					'to_module'=> 'Users',
					'language'=>'pt_br'
				),
				array(
					'from'=> '<basepath>/language/modules/Users/ru_ru.users.php',
					'to_module'=> 'Users',
					'language'=>'ru_ru'
				),

				//orgchart addon
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_en_gb.php',
					  'to_module'=> 'Accounts',
					  'language'=>'en_gb'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_en_gb.php',
					  'to_module'=> 'Contacts',
					  'language'=>'en_gb'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_en_gb.php',
					  'to_module'=> 'Employees',
					  'language'=>'en_gb'
				),
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_en_us.php',
					  'to_module'=> 'Accounts',
					  'language'=>'en_us'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_en_us.php',
					  'to_module'=> 'Contacts',
					  'language'=>'en_us'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_en_us.php',
					  'to_module'=> 'Employees',
					  'language'=>'en_us'
				),
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_ge_ge.php',
					  'to_module'=> 'Accounts',
					  'language'=>'ge_ge'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_ge_ge.php',
					  'to_module'=> 'Contacts',
					  'language'=>'ge_ge'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_ge_ge.php',
					  'to_module'=> 'Employees',
					  'language'=>'ge_ge'
				),
				array('from'=> '<basepath>/language/modules/Accounts/mod_strings_fr_FR.php',
					  'to_module'=> 'Accounts',
					  'language'=>'fr_FR'
				),
				array('from'=> '<basepath>/language/modules/Contacts/mod_strings_fr_FR.php',
					  'to_module'=> 'Contacts',
					  'language'=>'fr_FR'
				),
				array('from'=> '<basepath>/language/modules/Employees/mod_strings_fr_FR.php',
					  'to_module'=> 'Employees',
					  'language'=>'fr_FR'
				),
			),
			'beans'    => array(
				array(
					'module' => 'TeamsOS',
					'class'  => 'TeamOS',
					'path'   => 'modules/TeamsOS/TeamOS.php',
					'tab'    => false
				)
			),

			'custom_fields'=>array(
				//will be referenced as assigned_team_id_c   - _c indicates a custom field
				//current types are varchar,textarea,double,float,int,date,bool,enum (select), relate
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Accounts'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Bugs'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Calls'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Campaigns'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Cases'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Contacts'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Documents'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Leads'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Meetings'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Notes'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Opportunities'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Project'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'ProjectTask'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Prospects'
				),
				array('name'=>'assigned_team_id',
							'label'=>'Assigned to Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>1,
							'module'=>'Tasks'
				),
				array('name'=>'default_team_id',
							'label'=>'Default Team',
							'type'=>'enum',
							'max_size'=>36,
							'require_option'=>'optional',
							'default_value'=>'',
							'ext1'=>'teams_array',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>1,
							'mass_update'=>0,
							'module'=>'Users'
				),
				array('name'=>'show_all_teams',
							'label'=>'Show All Teams',
							'type'=>'bool',
							'require_option'=>'optional',
							'default_value'=>'0',
							'ext1'=>'',
							'ext2'=>'',
							'ext3'=>'',
							'audited'=>0,
							'mass_update'=>1,
							'module'=>'Users'
				),
			),
			'relationships' => array(
				array(
					'module'            => 'TeamsOS',
					'meta_data'         => '<basepath>/newfiles/metadata/team_membershipMetaData.php',
					'module_vardefs'    => '<basepath>/newfiles/TeamsOS/vardefs.php',
					'module_layoutdefs' => '<basepath>/newfiles/TeamsOS/layout_defs.php'
				)
			)
		);
	}
?>
