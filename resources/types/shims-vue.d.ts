declare module 'vue3-tabs-component'{
    export { Tabs, Tab }
}

declare module '*.vue' {
    import type { DefineComponent } from 'vue'
    const component: DefineComponent<{}, {}, any>
    export default component
}