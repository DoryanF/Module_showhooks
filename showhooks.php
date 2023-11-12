<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

use PhpParser\Node\Expr\BinaryOp\Equal;
use PrestaShop\PrestaShop\Adapter\Entity\Page;
use PrestaShop\PrestaShop\Core\Domain\Meta\Query\GetPagesForLayoutCustomization;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use Symfony\Component\HttpKernel\DataCollector\AjaxDataCollector;
use Symfony\Component\Validator\Constraints\EqualTo;

 if(!defined('_PS_VERSION_'))
 {
    exit;
 }

class ShowHooks extends Module
{

    public $hook = [

    ];

    public $configuration_fields = [
        'VISUALISATION_NL',
        'SHOW_DISPLAY_HOOKS',
        'SHOW_ACTIONS_HOOKS',
        'SHOW_ALL_HOOKS',
        'SHOW_ADMIN_HOOKS',
        'SHOW_PAGE_HOOKS',
        'SHOW_PAGE',
        '_MY_IP_CONNECT',
        'COLOR_CSS'
    ];


    public function __construct() {
        $this->name = 'showhooks';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Doryan Fourrichon';

        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Show Hooks');
        $this->description = $this->l('Le module pour afficher les Hooks !');


        $this->confirmUninstall = $this->l('Are you sure you want to uninstall');
       
    }

    public function install()
    {
        if(!parent::install()||
        !Configuration::updateValue('VISUALISATION_NL','') ||
        !Configuration::updateValue('SHOW_ACTION_DISPLAY_HOOK','') ||
        !Configuration::updateValue('SHOW_ALL_HOOKS','') ||
        !Configuration::updateValue('SHOW_ADMIN_HOOKS','') ||
        !Configuration::updateValue('SHOW_PAGE_HOOKS','') ||
        !Configuration::updateValue('SHOW_PAGE','') ||
        !Configuration::updateValue('_MY_IP_CONNECT','') ||
        !Configuration::updateValue('COLOR_CSS','cyan') ||
        !$this->registerHook('header') ||
        !$this->registerHook('backOfficeHeader')
        )
        {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        foreach ($this->configuration_fields as $key)
        {
            Configuration::deleteByName($key);
        }
        return parent::uninstall();
    }

    public function getContent()
    {
        return $this->postProcess().$this->renderForm();
    }

    public function renderForm()
    {
        $querybus = $this->get('prestashop.core.query_bus');
        $pages = $querybus->handle(new GetPagesForLayoutCustomization());
        $options = array();

        foreach ($pages as $page)
        {
            $options[] = array(
                "id_option" => $page->getpage(),
                "name" => $page->gettitle()
            );
        }

        $field_forms_switch = [
            "form" => [
                'legend' => [
                    'title' => $this->l('Configuration'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show the hook visualisation'),
                        'name' => 'VISUALISATION_NL',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('show the action hook only'),
                        'name' => 'SHOW_ACTIONS_HOOKS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show only display hooks'),
                        'name' => 'SHOW_DISPLAY_HOOKS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('show all hooks'),
                        'name' => 'SHOW_ALL_HOOKS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('show admin hooks'),
                        'name' => 'SHOW_ADMIN_HOOKS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                    ],
                    [
                        'type' => 'textbutton',
                        'label' => $this->l('Your IP address'),
                        'name' => '_MY_IP_CONNECT',
                        'required' => true,
                        'button' => [
                            'label' => $this->l('Add IP'),
                            'class' => 'btn btn-primary',
                            'name' => 'btn_add_ip',
                            'attributes' => [
                                'onclick' => 'document.getElementById("_MY_IP_CONNECT").value = "' . Tools::getRemoteAddr() . '"; return false;'
                            ]
                        ],
                    ],
                    [
                        'type' => 'submit',
                        'title' => $this->l('Add your IP address'),
                        'name' => 'SUBMIT_ADD_IP',
                        'icon' => 'icon-foo',
                        'class' => 'btn btn-primary',
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('show hook on frontoffice page'),
                        'name' => 'SHOW_PAGE_HOOKS',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'label2_on',
                                'value' => 1,
                                'label' => $this->l('Oui')
                            ),
                            array(
                                'id' => 'label2_off',
                                'value' => 0,
                                'label' => $this->l('Non')
                            )
                        )
                    ],
                    [
                        'type' => 'select',                              // This is a <select> tag.
                        'label' => $this->l('choose a page:'),         // The <label> for this <select> tag.
                        'desc' => $this->l('Choose a page'),   // A help text, displayed right next to the <select> tag.
                        'name' => 'SHOW_PAGE',                     // The content of the 'id' attribute of the <select> tag.
                        'required' => true,                              // If set to true, this option must be set.
                        'options' => [
                            'query' => $options,                           // $options contains the data itself.
                            'id' => 'id_option',                           // The value of the 'id' key must be the same as the key for 'value' attribute of the <option> tag in each $options sub-array.
                            'name' => 'name'                               // The value of the 'name' key must be the same as the key for the text content of the <option> tag in each $options sub-array.
                        ]
                    ],
                    [
                        'type' => 'color',
                        'label' => $this->l('Choose color'),
                        'name' => 'COLOR_CSS'
                    ],

                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-primary',
                    'name' => 'saving'
                     ]
                ],
            ];


        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->fields_value['VISUALISATION_NL'] = Configuration::get('VISUALISATION_NL');
        $helper->fields_value['SHOW_ACTIONS_HOOKS'] = Configuration::get('SHOW_ACTIONS_HOOKS');
        $helper->fields_value['SHOW_DISPLAY_HOOKS'] = Configuration::get('SHOW_DISPLAY_HOOKS');
        $helper->fields_value['SHOW_ALL_HOOKS'] = Configuration::get('SHOW_ALL_HOOKS');
        $helper->fields_value['SHOW_ADMIN_HOOKS'] = Configuration::get('SHOW_ADMIN_HOOKS');
        $helper->fields_value['SHOW_PAGE_HOOKS'] = Configuration::get('SHOW_PAGE_HOOKS');
        $helper->fields_value['SHOW_PAGE'] = Configuration::get('SHOW_PAGE');
        $helper->fields_value['_MY_IP_CONNECT'] = Configuration::get('_MY_IP_CONNECT');
        $helper->fields_value['COLOR_CSS'] = Configuration::get('COLOR_CSS');

        return $helper->generateForm([$field_forms_switch]);
    }


    public function postProcess()
    {
       if(Tools::isSubmit('saving'))
       {
        Configuration::updateValue('VISUALISATION_NL', Tools::getValue('VISUALISATION_NL'));
        Configuration::updateValue('SHOW_ACTIONS_HOOKS', Tools::getValue('SHOW_ACTIONS_HOOKS'));
        Configuration::updateValue('SHOW_DISPLAY_HOOKS', Tools::getValue('SHOW_DISPLAY_HOOKS'));
        Configuration::updateValue('SHOW_ALL_HOOKS', Tools::getValue('SHOW_ALL_HOOKS'));
        Configuration::updateValue('SHOW_ADMIN_HOOKS', Tools::getValue('SHOW_ADMIN_HOOKS'));
        Configuration::updateValue('SHOW_PAGE_HOOKS', Tools::getValue('SHOW_PAGE_HOOKS'));
        Configuration::updateValue('SHOW_PAGE', Tools::getValue('SHOW_PAGE') );
        Configuration::updateValue('_MY_IP_CONNECT', Tools::getValue('_MY_IP_CONNECT'));
        Configuration::updateValue('COLOR_CSS',Tools::getValue('COLOR_CSS'));
       }
        
    }

    public function hookHeader($params)
    {        
        return $this->context->controller->addCSS($this->_path.'views/css/hook.css');
    }
    
    public function hookBackOfficeHeader($params)
    {
        if (Tools::getValue('configure') == $this->name) {

           return $this->context->controller->addCSS($this->_path . 'views/css/hook.css');
        }
    }

}