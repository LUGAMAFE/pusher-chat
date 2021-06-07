/// <reference types="jquery" />
/// <reference types="datatables.net"/>

declare namespace DataTables {

    interface Settings {
        select?: SelectSettings;
    }

    interface SelectSettings {
        style?: string;
        selector?: string;
    }
    //#endregion "checkboxes-settings
}