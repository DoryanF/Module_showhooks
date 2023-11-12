<?php

class Hook extends HookCore
{
    public static function exec(
        $hook_name,
        $hook_args = [],
        $id_module = null,
        $array_return = false,
        $check_exceptions = true,
        $use_push = false,
        $id_shop = null,
        $chain = false
    ) {


        $status = Configuration::get('VISUALISATION_NL');
        
        $allHooksStatus = Configuration::get('SHOW_ALL_HOOKS');
        $hooksActionStatus = Configuration::get('SHOW_ACTIONS_HOOKS');
        $hooksDisplayStatus = Configuration::get('SHOW_DISPLAY_HOOKS');
        $hooksAdminStatus = Configuration::get('SHOW_ADMIN_HOOKS');
        $showPageStatus = Configuration::get('SHOW_PAGE_HOOKS');
        $showPage = Configuration::get('SHOW_PAGE');
        $ipConnect = Configuration::get('_MY_IP_CONNECT');
        $cssInput = Configuration::get('COLOR_CSS');
        

        if($status == 1)
        {
            $output = '';

            if($_SERVER['REMOTE_ADDR'] == $ipConnect)
            {
                    if($allHooksStatus == 1)
                {
                    echo $output .= '<h4 id="titlehook" style="background-color: ' . $cssInput . ';">' . $hook_name . '</h4>';


                }else {
                    $output .= '';
                }
                
                if($hooksActionStatus == 1)
                {
                    if(strpos($hook_name,'action') !== false)
                    {
                        echo $output .= '<h4 id="titlehook" style="background-color: ' . $cssInput . ';">' . $hook_name . '</h4>';
                    }else{
                        $output .= '';
                    }
                }else{
                    $output .= '';
                }
                if($hooksDisplayStatus == 1)
                {
                    if(strpos($hook_name,'display') !== false)
                    {
                        echo $output .= '<h4 id="titlehook" style="background-color: ' . $cssInput . ';">' . $hook_name . '</h4>';
                    }else{
                        $output .= '';
                    }
                }else{
                    $output .= '';
                }
                if($hooksAdminStatus == 1)
                {
                    if(strpos($hook_name,'Admin') !== false)
                    {
                        echo $output .= '<h4 id="titlehook" style="background-color: ' . $cssInput . ';">' . $hook_name . '</h4>';
                    }else{
                        $output .= '';
                    }
                }else{
                    $output .= '';
                }
                if($showPageStatus == 1)
                {

                    if(Context::getContext()->controller !== null && Context::getContext()->controller->php_self == $showPage)
                    {
                        echo $output .= '<h4 id="titlehook" style="background-color: ' . $cssInput . ';">' . $hook_name . '</h4>';
                    }
                }
            }
            else{
                $output .= '';
            }
            
        }
        return parent::exec($hook_name, $hook_args, $id_module, $array_return, $check_exceptions);
        
    }
}