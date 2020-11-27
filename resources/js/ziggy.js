const Ziggy = {"url":"http:\/\/booj.test","port":null,"defaults":{},"routes":{"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.update":{"uri":"reset-password","methods":["POST"]},"register":{"uri":"register","methods":["GET","HEAD"]},"user-profile-information.update":{"uri":"user\/profile-information","methods":["PUT"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirm":{"uri":"user\/confirm-password","methods":["GET","HEAD"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"two-factor.login":{"uri":"two-factor-challenge","methods":["GET","HEAD"]},"profile.show":{"uri":"user\/profile","methods":["GET","HEAD"]},"api-tokens.index":{"uri":"user\/api-tokens","methods":["GET","HEAD"]},"livewire.upload-file":{"uri":"livewire\/upload-file","methods":["POST"]},"livewire.preview-file":{"uri":"livewire\/preview-file\/{filename}","methods":["GET","HEAD"]},"api.books.search":{"uri":"api\/books\/search","methods":["GET","HEAD"]},"api.books.index":{"uri":"api\/books","methods":["GET","HEAD"]},"api.books.store":{"uri":"api\/books","methods":["POST"]},"api.books.show":{"uri":"api\/books\/{book}","methods":["GET","HEAD"],"bindings":{"book":"id"}},"api.books.update":{"uri":"api\/books\/{book}","methods":["PUT","PATCH"],"bindings":{"book":"id"}},"api.books.destroy":{"uri":"api\/books\/{book}","methods":["DELETE"],"bindings":{"book":"id"}},"home":{"uri":"home","methods":["GET","HEAD"]},"books.show":{"uri":"books\/{book}","methods":["GET","HEAD"],"bindings":{"book":"id"}}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    for (let name in window.Ziggy.routes) {
        Ziggy.routes[name] = window.Ziggy.routes[name];
    }
}

export { Ziggy };
