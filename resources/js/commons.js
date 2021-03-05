import { mapGetters } from "vuex";
import Loader from "./components/circleLoader";
import errorText from "./components/errorText";
export default {
    data() {
        return {
            // siteUri: 'http://bookingmarket.localhost/',
        };
    },
    components: {
        Loader,
        errorText
    },

    mounted() { },
    computed: {
        ...mapGetters({
            authUser: "getAuthUser",
            divisions: "getDivisions",
            districts: "getDistricts",
            upazilas: "getUpazilas",
            cities: "getCities",
            wards: "getWards",
            areas: "getAreas",
        })
    },
    filters: {
        formatDate(value) {
            return new Date(value).toDateString();
        },

        formatDateTime(value) {
            return new Date(value).toLocaleString("en-US", { hour12: true });
        }
    },
    methods: {
        async callApi(method, url, dataObj) {
            try {
                let data = await axios({
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json"
                    },
                    method: method,
                    url: url,
                    data: dataObj
                });
                return data;
            } catch (e) {
                return e.response;
            }
        },

        i(msg, i = "Hey!") {
            this.$Notice.info({
                title: i,
                desc: msg
            });
        },
        s(msg, i = "Great!") {
            this.$Notice.success({
                title: i,
                desc: msg
            });
        },
        w(msg, i = "Hi!") {
            this.$Notice.warning({
                title: i,
                desc: msg
            });
        },
        e(msg, i = "Oops!") {
            this.$Notice.error({
                title: i,
                desc: msg
            });
        },
        swr() {
            this.$Notice.error({
                title: "Oops",
                desc: "Something went wrong, please try again later"
            });
        },
        ns(title) {
            this.$Message.success(title);
        },
        ni(title) {
            this.$Message.info(title);
        },
        nw(title) {
            this.$Message.warning(title);
        },
        ne(title) {
            this.$Message.error(title);
        },
        nswr() {
            this.$Message.error("Something went wrong, please try again later");
        },

        validateEmail(email) {
            const reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
            if (!reg.test(email)) {
                return false;
            }
            return true;
        }
    }
};
