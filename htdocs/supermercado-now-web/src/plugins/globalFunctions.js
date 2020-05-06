const GlobalFunctions = {};
GlobalFunctions.install = function (Vue) {
    Vue.prototype.hasPermission = function (permisson) {
        return this.$acl.get.indexOf(permisson) > -1;
    }

    Vue.prototype.showErrorMessages = function (error) {
        let messages = "";
        if (error.response.data.errors) {
            for (let msg of Object.values(error.response.data.errors)) {
                messages += msg + " \n";
            }
        }
        this.$swal.fire({
            icon: "error",
            title: error.response.data.message,
            text: messages,
            showConfirmButton: true
        });
    }

    Vue.prototype.showSuccessMessage = function (message) {
        this.$swal.fire({
            icon: "success",
            title: message,
            showConfirmButton: false,
            timer: 1500
        });
    }
}
export default GlobalFunctions;