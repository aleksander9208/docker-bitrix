{"version":3,"sources":["header-and-button.bundle.js"],"names":["this","BX","Landing","Ui","Panel","Formsettingspanel","exports","landing_ui_card_headercard","main_core","landing_ui_card_messagecard","landing_ui_form_formsettingsform","landing_ui_field_textfield","landing_ui_field_variablesfield","landing_ui_panel_basepresetpanel","headerAndButtonsIcon","HeaderAndButtonContent","_ContentWrapper","babelHelpers","inherits","options","_this","classCallCheck","possibleConstructorReturn","getPrototypeOf","call","setEventNamespace","header","HeaderCard","title","Loc","getMessage","message","MessageCard","id","icon","description","restoreState","more","helper$$1","Reflection","getClass","Helper","show","headersForm","FormSettingsForm","fields","VariablesField","selector","textOnly","content","formOptions","data","variables","getPersonalizationVariables","desc","UI","Field","Checkbox","items","value","name","compact","buttonsForm","TextField","buttonCaption","addItem","createClass","key","_this2","cache","remember","dictionary","personalization","list","map","item","valueReducer","sourceValue","Object","entries","reduce","acc","_ref","_ref2","slicedToArray","includes","hideDesc","useSign","objectSpread","getSwitch","getValue","onChange","event","emit","getData","skipPrepare","ContentWrapper","default","Content","Card","Form"],"mappings":"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","file":"header-and-button.bundle.map.js"}