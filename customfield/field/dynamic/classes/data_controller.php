<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.



namespace customfield_dynamic;

use core_customfield\api;

defined('MOODLE_INTERNAL') || die;

class data_controller extends \core_customfield\data_controller {

    /**
     * Return the name of the field where the information is stored
     * @return string
     */
    public function datafield() : string {
        return 'value';
    }

    /**
     * Returns the default value as it would be stored in the database (not in human-readable format).
     *
     * @return mixed
     */
    public function get_default_value() {
        $defaultvalue = $this->get_field()->get_configdata_property('defaultvalue');
        $defaultvalues = '';
        if ('' . $defaultvalue !== '') {
            $options = field_controller::get_options_array($this->get_field());
            $values = explode(",", $defaultvalue);
            foreach ($values as $value) {
                $key = array_key_exists($value, $options);
                if ($key !== false) {
                    $defaultvalues .= (empty($defaultvalues) ? '' : ', ') . $value;
                }
            }
        }

        return $defaultvalues;
    }

    /**
     * Add fields for editing a textarea field.
     *
     * @param \MoodleQuickForm $mform
     */
    public function instance_form_definition(\MoodleQuickForm $mform) {
        global $DB;
        $id = optional_param('category', 0 , PARAM_INT);
        // $field = $this->get_field();
        // $config = $field->get('configdata');
        // $options = field_controller::get_options_array($field);
        // $formattedoptions = array();
        // $attributes = array();
        // if ($this->get_field()->get_configdata_property('multiselect')) {
        //     $attributes = array('multiple' => true);
        // }
        // $context = $this->get_field()->get_handler()->get_configuration_context();
        // foreach ($options as $key => $option) {
        //     // Multilang formatting with filters.
        //     $formattedoptions[$key] = format_string($option, true, ['context' => $context]);
        // }

        // $elementtype = 'select';
        // if ($this->get_field()->get_configdata_property('autocomplete')) {
        //     $elementtype = 'autocomplete';
        // }
        // $elementname = $this->get_form_element_name();
        // $mform->addElement($elementtype, $elementname, $this->get_field()->get_formatted_name(),
        // $formattedoptions, $attributes);

        // if ($field->get_configdata_property('required')) {
        //     $mform->addRule($elementname, null, 'required', null, 'client');
        // }
        $qcategory = $DB->get_record('local_questionbank',array('quesionbankid'=>$id));

         $courseslist= $DB->get_records_sql_menu("SELECT id,fullname FROM {course} as c 
            WHERE id IN ($qcategory->course)");
        $courses = array(null => get_string('select_course', 'local_questionbank')) + $courseslist ;
        $courseselect = $mform->addElement('autocomplete','course', get_string('courses', 'local_questionbank'), $courses);
        $mform->addRule('course', get_string('courserequired','local_questionbank'), 'required', null);
        $courseselect->setMultiple(false);


        $competencieslist= $DB->get_records_sql_menu("SELECT id,name FROM {local_competencies} as lc 
            WHERE id IN ($qcategory->competency)");

        $competencies = array(null => get_string('select_comptencies', 'local_questionbank')) + $competencieslist ;
        $competencyselect = $mform->addElement('autocomplete','competency', get_string('competencies', 'local_questionbank'), $competencies);
        $mform->addRule('competency', get_string('competencyrequired','local_questionbank'), 'required', null);
        $competencyselect->setMultiple(false); 
    }
    /**
     * Prepares the custom field data related to the object to pass to mform->set_data() and adds them to it
     *
     * This function must be called before calling $form->set_data($object);
     *
     * @param \stdClass $instance the instance that has custom fields, if 'id' attribute is present the custom
     *    fields for this instance will be added, otherwise the default values will be added.
     */
    public function instance_form_before_set_data(\stdClass $instance) {
        $instance->{$this->get_form_element_name()} = implode(',', $this->get_value());
    }
    /**
     * Saves the data coming from form
     *
     * @param \stdClass $datanew data coming from the form
     * @throws \coding_exception
     */
    public function instance_form_save(\stdClass $datanew) {
        $elementname = $this->get_form_element_name();
        if (!property_exists($datanew, $elementname)) {
            return;
        }
        $value = $datanew->$elementname;
        if ($this->get_field()->get_configdata_property('multiselect')) {
            $value = implode(',', $datanew->$elementname);
        }

        $this->data->set($this->datafield(), $value);
        $this->data->set('value', $value);
        $this->save();
    }
    /**
     * Validates data for this field.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function instance_form_validation(array $data, array $files) : array {
        $errors = parent::instance_form_validation($data, $files);
        if ($this->get_field()->get_configdata_property('required')) {
            // Standard required rule does not work on dynamic element.
            $elementname = $this->get_form_element_name();
            if (empty($data[$elementname])) {
                $errors[$elementname] = get_string('err_required', 'form');
            }
        }
        return $errors;
    }

    /**
     * Returns the value as it is stored in the database or default value if data record is not present
     *
     * @return array
     */
    public function get_value() {
        if (!$this->get('id')) {
            return explode(',', $this->get_default_value());
        }
        return explode(',', $this->get($this->datafield()));
    }

    /**
     * Checks if the value is empty
     *
     * @param mixed $value
     * @return bool
     */
    protected function is_empty($value): bool {
        return empty($value);
    }

    /**
     * Returns value in a human-readable format
     *
     * @return mixed|null value or null if empty
     */
    public function export_value() {
        $value = $this->get_value();

        if ($this->is_empty($value)) {
            return null;
        }
        $completevalue = '';
        $options = field_controller::get_options_array($this->get_field());
        foreach ($value as $val) {
            if (array_key_exists($val, $options)) {
                $completevalue .= (empty($completevalue) ? '' : ', ') .
                 format_string($options[$val], true,
                 ['context' => $this->get_field()->get_handler()->get_configuration_context()]);
            }
        }

        return $completevalue;
    }
}
