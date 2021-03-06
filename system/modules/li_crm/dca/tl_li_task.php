<?php

/**
 * @copyright   Liplex Webprogrammierung und -design Christian Kolb 2011
 * @author      Christian Kolb <info@liplex.de>
 * @author      ApoY2k <apoy2k@gmail.com>
 * @author      Tristan Lins <tristan.lins@infinitysoft.de>
 * @author      Darko Selesi <hallo@w3scouts.com>
 * @license     MIT (see /LICENSE.txt for further information)
 */

/**
 * Table tl_li_task
 */
$this->loadLanguageFile('tl_li_task_reminder');

$GLOBALS['TL_DCA']['tl_li_task'] = array
(
	// Config
	'config' => array
	(
	    'dataContainer'             => 'Table',
		'ctable'                    => array('tl_li_task_comment'),
		'enableVersioning'          => true,
		'onsubmit_callback'			=> array
		(
			array('LiCRM\Task', 'onSubmit')
		),
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
	),
	
	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                  => 1,
			'fields'                => array('toStatus', 'priority'),
			'flag'                  => 1,
			'panelLayout'           => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                => array('title'),
			'label_callback'        => array('LiCRM\Task', 'renderLabel')
		),
		'global_operations' => array
		(
            'reminder' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['reminder'],
				'href'              => 'table=tl_li_task_reminder',
				'class'             => 'header_task_reminder',
                'icon'              => 'system/modules/li_crm/assets/manage_reminders.png',
				'attributes'        => 'onclick="Backend.getScrollOffset();"'
			),
            'all' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'              => 'act=select',
				'class'             => 'header_edit_all',
				'attributes'        => 'onclick="Backend.getScrollOffset();"'
			)
		),
		'operations' => array
		(
			'comment' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['comment'],
				'href'              => 'table=tl_li_task_comment&amp;act=create&amp;mode=2',
				'icon'              => 'system/modules/li_crm/assets/comment.png',
				'button_callback'   => array('LiCRM\Task', 'commentTaskIcon')
			),
			'copy' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['copy'],
				'href'              => 'act=copy',
				'icon'              => 'copy.gif'
			),
			'delete' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['delete'],
				'href'              => 'act=delete',
				'icon'              => 'delete.gif',
				'attributes'        => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['toggle'],
				'icon'              => 'visible.gif',
				'attributes'        => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'   => array('LiCRM\Task', 'toggleIcon')
			),
			'show' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['show'],
				'href'              => 'act=show',
				'icon'              => 'show.gif'
			),
			'new_reminder' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task_reminder']['new'],
				'href'              => 'table=tl_li_task_reminder&act=create',
				'icon'              => 'system/modules/li_crm/assets/reminder_add.png'
			),
			'done' => array
			(
				'label'             => &$GLOBALS['TL_LANG']['tl_li_task']['done'],
				'icon'              => 'system/modules/li_crm/assets/task_done_disabled.png',
				'attributes'        => 'onclick="Backend.getScrollOffset();"',
				'button_callback'   => array('LiCRM\Task', 'taskDoneIcon')
			)
		)
	),
	
	// Palettes
	'palettes' => array
	(
		'__selector__'              => array(''),
		'default'                   => '{task_legend}, title, alias, deadline, description;
		                                {settings_legend}, toCustomer, toProject, toStatus, toUser, priority;
										{publish_legend},published;'
	),
	
	// Fields
	'fields' => array
	(
        'id' => array(
            'sql'                   => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
		(
			'default' => time(),
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
		),
        'toCustomer' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['toCustomer'],
			'inputType'             => 'select',
			'filter'                => true,
            'exclude'   			=> true,
			'options_callback'      => array('LiCRM\Customer', 'getCustomerOptions'),
			'eval'                  => array('tl_class'=>'w50', 'chosen'=>true, 'includeBlankOption'=>true, 'submitOnChange'=>true),
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
		),
        'toProject' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['toProject'],
			'inputType'             => 'select',
			'exclude'   			=> true,
			'options_callback'      => array('LiCRM\Project', 'getProjectsOfCustomer'),
            'eval'                  => array('tl_class'=>'w50', 'chosen'=>true, 'includeBlankOption'=>true),
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
		),
        'toStatus' => array
		(
            'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['toStatus'],
			'filter'                => true,
			'inputType'             => 'select',
			'exclude'   			=> true,
            'foreignKey'            => 'tl_li_task_status.title',
			'eval'                  => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50', 'mandatory'=>true),
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
        ),
        'toUser' => array
		(
            'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['toUser'],
			'filter'                => true,
			'inputType'             => 'select',
			'exclude'   			=> true,
            'foreignKey'            => 'tl_user.username',
			'eval'                  => array('tl_class'=>'w50', 'chosen'=>true),
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
        ),
        'priority' => array
		(
            'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['priority'],
			'filter'                => true,
			'inputType'             => 'select',
			'exclude'   			=> true,
			'options_callback'      => array('LiCRM\Task', 'getPriorityOptions'),
			'eval'                  => array('tl_class'=>'w50', 'chosen'=>true),
            'sql'                   => "int(3) unsigned NOT NULL default '0'"
        ),
        'title' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['title'],
			'inputType'             => 'text',
			'exclude'   			=> true,
			'search'                => true,
			'eval'                  => array('mandatory'=>true, 'maxlength'=>250, 'tl_class'=>'w50'),
            'sql'                   => "varchar(255) NOT NULL default ''"
		),
		'alias' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['alias'],
			'inputType'             => 'text',
			'exclude'   			=> true,
			'eval'                  => array('rgxp'=>'alnum', 'unique'=>true, 'spaceToUnderscore'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
			'save_callback' 		=> array
			(
				array('LiCRM\Task', 'generateAlias')
			),
            'sql'                   => "varchar(64) NOT NULL default ''"
		),
		'deadline' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['deadline'],
			'default'               => time(),
			'filter'                => true,
			'sorting'               => true,
			'flag'                  => 8,
			'inputType'             => 'text',
			'exclude'   			=> true,
			'eval'                  => array('rgxp'=>'date', 'datepicker'=>$this->getDatePickerString(), 'tl_class'=>'w50 wizard'),
            'sql'                   => "varchar(10) NOT NULL default ''"
		),
		'description' => array
		(
            'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['description'],
			'search'                => true,
			'inputType'             => 'textarea',
			'exclude'   			=> true,
			'eval'                  => array('tl_class'=>'clr', 'rte'=>'tinyMCE'),
            'sql'                   => "text NULL"
        ),
		'published' => array
		(
            'label'                 => &$GLOBALS['TL_LANG']['tl_li_task']['published'],
			'inputType'             => 'checkbox',
			'exclude'   			=> true,
			'filter'                => true,
            'sql'                   => "char(1) NOT NULL default ''"
        )
	)
);