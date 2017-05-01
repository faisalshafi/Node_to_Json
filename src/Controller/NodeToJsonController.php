<?php

namespace Drupal\node_to_json\Controller;

/**
  @file
  Contains \Drupal\node_to_json\Controller\NodeToJsonController.
 */
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

class NodeToJsonController extends ControllerBase {

    /**
     * Serve node JSON data on uri http://localhost8001/page_json/apikey/nid
     * 
     * @return array
     */
    public function PageAccess($apikey,NodeInterface $page_node) {
        $siteapikey = \Drupal::config('system.site')->get('siteapikey');
        if ($apikey === $siteapikey) {
            if (!is_object($page_node)) {   // If not Node_id
                echo t('Problem in Content loading ...!');
                die();
            }
            if ($page_node->getType() === 'page') {
                $serializer = \Drupal::service('serializer');
                $node_jsondata = $serializer->serialize($page_node, 'json', ['plugin_id' => 'entity']);
                echo $node_jsondata;
                die();
            }
            echo t('Content Type Mismatch!');
            die();
        }
        echo t('Access Denied!');  //without proper apikey you are not allowed.
        die();
    }

    /**
     * This we can use for custom form validation.
     */
    public function site_apikey_form_validate($form, \Drupal\Core\Form\FormStateInterface $form_state) {
        
     
    
        if (strlen($form['site_information']['siteapikey']['#value']) !== 16) {
            $form_state->setErrorByName('siteapikey', t('Not Less or Greater then 16 characters !!!'));
        }
    }

    /**
     * custom form submission
     */
    public function site_apikey_form_submit($form, \Drupal\Core\Form\FormStateInterface $form_state) {
        $site_apikey_setting = $form['site_information']['siteapikey']['#value'] ? $form['site_information']['siteapikey']['#value'] : t('No API Key yet');
        \Drupal::configFactory()->getEditable('system.site')->set('siteapikey', $site_apikey_setting)->save();
        if ($form_state->getValue('siteapikey') != NULL || $form_state->getValue('siteapikey') != '') {
    
    $message = t('Site API Key has been saved with that value');
    drupal_set_message($message);
  }
    }

}
