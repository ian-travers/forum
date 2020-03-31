let user = window.App.user;

let authorizations = {
    owns(model, prop = 'user_id') {
        return model[prop] === user.id;
    },

    isAdmin() {
        return ['Ian Travers', 'Dana Ashbrook'].includes(user.name);
    }
};

module.exports = authorizations;
