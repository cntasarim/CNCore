<?php
namespace App\Core;

/**
 * Base Controller Class
 * 
 * All controllers should extend this class
 */
abstract class Controller
{
    /**
     * Render a view
     * 
     * @param string $view View file to render
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function view($view, $data = [])
    {
        View::render($view, $data);
    }
    
    /**
     * Redirect to a URL
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    protected function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Redirect to a named route
     * 
     * @param string $name Route name
     * @param array $params Route parameters
     * @return void
     */
    protected function redirectToRoute($name, $params = [])
    {
        $url = Router::generateUrl($name, $params);
        $this->redirect($url);
    }
    
    /**
     * Validate request data
     * 
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @return array Array of errors (empty if validation passes)
     */
    protected function validate($data, $rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            // Split rules
            $fieldRules = explode('|', $rule);
            
            foreach ($fieldRules as $fieldRule) {
                // Check if rule has parameters
                if (strpos($fieldRule, ':') !== false) {
                    list($ruleName, $ruleParam) = explode(':', $fieldRule, 2);
                } else {
                    $ruleName = $fieldRule;
                    $ruleParam = null;
                }
                
                // Apply rule
                switch ($ruleName) {
                    case 'required':
                        if (!isset($data[$field]) || trim($data[$field]) === '') {
                            $errors[$field][] = ucfirst($field) . ' is required';
                        }
                        break;
                        
                    case 'email':
                        if (isset($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = ucfirst($field) . ' must be a valid email';
                        }
                        break;
                        
                    case 'min':
                        if (isset($data[$field]) && strlen($data[$field]) < $ruleParam) {
                            $errors[$field][] = ucfirst($field) . ' must be at least ' . $ruleParam . ' characters';
                        }
                        break;
                        
                    case 'max':
                        if (isset($data[$field]) && strlen($data[$field]) > $ruleParam) {
                            $errors[$field][] = ucfirst($field) . ' must not exceed ' . $ruleParam . ' characters';
                        }
                        break;
                        
                    case 'matches':
                        if (isset($data[$field]) && isset($data[$ruleParam]) && $data[$field] !== $data[$ruleParam]) {
                            $errors[$field][] = ucfirst($field) . ' must match ' . $ruleParam;
                        }
                        break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Get sanitized input
     * 
     * @param string $key Input key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed Sanitized input value
     */
    protected function input($key, $default = null)
    {
        $value = $_REQUEST[$key] ?? $default;
        
        if (is_string($value)) {
            return Security::sanitizeInput($value);
        }
        
        return $value;
    }
}