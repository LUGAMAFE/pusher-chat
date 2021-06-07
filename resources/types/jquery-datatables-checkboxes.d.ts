/// <reference types="jquery" />
/// <reference types="datatables.net"/>

declare namespace DataTables {

    interface ColumnDefsSettings extends ColumnSettings  {
        checkboxes?: CheckboxesSettings;
    }

    interface CheckboxesSettings {
        selectRow?: boolean;
    }

    interface ColumnMethods{
        checkboxes?: any;
    }
    //#endregion "checkboxes-settings
}