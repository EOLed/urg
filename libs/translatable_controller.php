<?php
App::import("Core", "L10n");
App::import("Sanitize");
class TranslatableController extends UrgAppController {
    function beforeFilter() {
        parent::beforeFilter();
        $this->{$this->modelClass}->locale = array($this->get_locale());
    }

    /**
     * Returns the entities that need a translation for $language.
     */
    function translate_queue($language = null) {
        $locale = $this->get_locale($language);
        $primary_key = $this->{$this->modelClass}->primaryKey;

        $sql = "SELECT `{$this->modelClass}`.$primary_key
                    FROM {$this->params["controller"]} `{$this->modelClass}`
                    WHERE `{$this->modelClass}`.$primary_key
                          NOT IN (SELECT DISTINCT foreign_key 
                                    FROM i18n 
                                    WHERE locale = '" . Sanitize::escape($locale, "default") . "' AND 
                                          model = '" . $this->modelClass . "')";
        $ids = $this->{$this->modelClass}->query($sql);

        $id_list = array();
        foreach ($ids as $id) {
            array_push($id_list, $id[$this->modelClass][$primary_key]);
        }

        $this->{$this->modelClass}->locale = "en_us";//array_keys($this->get_locales(array($locale)));
        CakeLog::write("debug", "finding results for locale: " . Debugger::exportVar($this->{$this->modelClass}->locale, 3));
        $results = $this->{$this->modelClass}->find("all", 
                array("conditions" => array($this->modelClass . ".$primary_key" => $id_list)));
        $this->set($this->params["controller"], $results);

        CakeLog::write("debug", "translatable {$this->params["controller"]}: " . Debugger::exportVar($results, 3));

        $this->set_locales();
    }

	function translate($id = null, $original_locale = null) {
        $model_class = $this->modelClass;
        $model_name = $this->{$model_class}->name;
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid ' . $model_name, true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
            $this->{$model_class}->locale = $this->data[$model_class]["locale"];
            $this->log("saving translatable data: " . Debugger::exportVar($this->data, 3), LOG_DEBUG);
			if ($this->{$model_class}->save($this->data)) {
				$this->Session->setFlash(__("The $model_name has been saved", true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__("The $model_name could not be saved. Please, try again.",     
                                            true));
			}
		}
		if (empty($this->data["Translation"])) {
            if (!$original_locale) {

            }
            $this->{$model_class}->locale = $original_locale;

			$this->data["Translation"] = is_numeric($id) ? $this->{$model_class}->findById($id) : 
                                                           $this->{$model_class}->findBySlug($id);
            $this->data[$model_class][$this->{$model_class}->primaryKey] =
                    $this->data["Translation"][$model_class][$this->{$model_class}->primaryKey];
            $this->log("viewing $model_name for translation: " .  Debugger::exportVar($this->data, 4), 
                       LOG_DEBUG);
		}

        $locales = $this->get_locales(array($original_locale));

        $this->set("locales", $locales);
	}

    function get_locales($excluded = array()) {
        $locales = $this->Session->read("Config.locales");

        foreach ($excluded as $exclude) {
            unset($locales[$exclude]);
        }

        return $locales;
    }

    function set_locales() {
        $this->set("locales", $this->Session->read("Config.locales"));
    }

    /**
     * @return The locale as specified by language. If no language has been specified, the session's current
     *         language will be used.
     */
    function get_locale($language = null) {
        $l10n = new L10n();
        if ($language == null) {
            $language = $this->Session->read("Config.language");          
            if ($language == null) {
                Configure::load("config");
                $language = Configure::read("Language.default");
            }
        }

        $catalog = $l10n->catalog($language);
        return $catalog["locale"];
    }
}
?>
