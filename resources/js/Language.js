import Messgs from './ll_messages';
import Lang from 'lang.js';

class Language {
    constructor() {
        Language.instance = new Lang({
            messages: Messgs
        });
        Language.instance.setFallback('en');
    }

    static getInstance() {
        if (Language.instance == null) {
            new Language();
        }
        return Language.instance;
    }
}

export default Language;