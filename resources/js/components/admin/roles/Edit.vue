<template>
    <div>
        <loader v-if="loader"></loader>
        <div v-else>
            <head-content :title="pageTitle" :buttons="buttons"></head-content>
            <form id="user-form" class="card" @submit.prevent="onSubmit" @change="keydown" @keydown="keydown">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <text-input-group v-model="data.label" :val="data.label" :error="errors.get('label')" title="عنوان"></text-input-group>
                                </div>
                                <div class="col-md-6">
                                    <text-input-group classes="ltr text-left" v-model="data.name" :val="data.name" :error="errors.get('name')" title="کلید"></text-input-group>
                                </div>
                                <div class="col-md-12">
                                    <select-group :options="[{title:'غیرفعال', value:'0'},{title:'فعال', value:'1'}]" title="پشتیبان تیکت" v-model="data.ticketDepartment" :val="data.ticketDepartment" :error="errors.get('ticketDepartment')"></select-group>
                                </div>
                            </div>
                            <multi-select-group v-model="data.canSet" :val="data.canSet" title="نقش هایی که من میتوانم به بقیه اختصاص دهم" name="canSet" :options="roles" :error="errors.get('canSet')"></multi-select-group>
                            <multi-select-group v-model="data.canSetMe" :val="data.canSetMe" title="نقش هایی که میتوانند من را به بقیه اختصاص دهند" name="canSetMe" :options="roles" :error="errors.get('canSetMe')"></multi-select-group>
                            <div class="d-none d-md-block">
                                <button :disabled="disabled" class="btn btn-success py-2 px-3">{{ buttonTitle }}</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label>مجوز ها</label>
                                <div class="vertical-tabbar">
                                    <ul class="tabbar-items">
                                        <li @click="setPermissionGroup('all')" :class="permissionGroup == 'all' ? 'active' : ''">
                                            <span>همه</span>
                                            <small :class="[ data.permissions.length == permissions.length ? 'text-success' : 'text-danger' ]">({{ data.permissions.length }}/{{ permissions.length }})</small>
                                        </li>
                                        <li @click="setPermissionGroup(name)" :class="permissionGroup == name ? 'active' : ''" v-for="(item, name) in permissionsGroup">
                                            <span v-text="item.title"></span>
                                            <small :class="[ item.count == item.list.length ? 'text-success' : 'text-danger' ]">({{ item.count }}/{{ item.list.length }})</small>
                                        </li>
                                    </ul>
                                    <div class="tabbar-contents flex-fill">
                                        <ul id="permissions-list">
                                            <label>
                                                <input @change="allPermissionModel($event)" :checked="allChecked" v-model="allChecked" type="checkbox">
                                                <span>انتخاب همه</span>
                                            </label>
                                            <div v-for="item in permissionsGroup">
                                                <li v-for="item2 in item.list">
                                                    <label :class="[{'d-none' : permissionsDisplay(item.name)}]">
                                                        <input :value="item2.id" v-model="data.permissions" type="checkbox" :checked="item2.active">
                                                        <span v-text="item2.title"></span>
                                                    </label>
                                                </li>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <label>&nbsp;</label>
                                <div class="vertical-tabbar">
                                    <ul class="tabbar-items">
                                        <li @click="otherGroup = 'postBoxes'" :class="otherGroup == 'postBoxes' ? 'active' : ''">
                                            <span>باکس ها</span>
                                            <small :class="[ data.postBoxes.length == Object.keys(postBoxes).length ? 'text-success' : 'text-danger' ]">({{ data.postBoxes.length }}/{{ Object.keys(postBoxes).length }})</small>
                                        </li>
                                        <li v-for="postType in postTypes" @click="otherGroup = postType.type" :class="otherGroup == postType.type ? 'active' : ''">
                                            <span v-text="postType.label"></span>
                                        </li>
                                        <li @click="otherGroup = 'countries'" :class="otherGroup == 'countries' ? 'active' : ''">
                                            <span>کشور</span>
                                            <small :class="[ data.countries.length == countries.length ? 'text-success' : 'text-danger' ]">({{ data.countries.length }}/{{ countries.length }})</small>
                                        </li>
                                        <li @click="otherGroup = 'provinces'" :class="otherGroup == 'provinces' ? 'active' : ''">
                                            <span>استان</span>
                                            <small :class="[ data.provinces.length == provinces.length ? 'text-success' : 'text-danger' ]">({{ data.provinces.length }}/{{ provinces.length }})</small>
                                        </li>
                                        <li @click="otherGroup = 'cities'" :class="otherGroup == 'cities' ? 'active' : ''">
                                            <span>شهرستان</span>
                                            <small :class="[ data.cities.length == cities.length ? 'text-success' : 'text-danger' ]">({{ data.cities.length }}/{{ cities.length }})</small>
                                        </li>
                                        <li @click="otherGroup = 'towns'" :class="otherGroup == 'towns' ? 'active' : ''">
                                            <span>شهر</span>
                                            <small :class="[ data.towns.length == towns.length ? 'text-success' : 'text-danger' ]">({{ data.towns.length }}/{{ towns.length }})</small>
                                        </li>
                                    </ul>
                                    <div class="tabbar-contents flex-fill">
                                        <ul>
                                            <div :class="otherGroup == 'postBoxes' ? '' : 'd-none'">
                                                <li>
                                                    <label>
                                                        <input @change="allPostBoxes($event)" type="checkbox">
                                                        <span>انتخاب همه</span>
                                                    </label>
                                                </li>
                                                <li v-for="(item, box) in postBoxes">
                                                    <label>
                                                        <input v-model="data.postBoxes" :value="box" type="checkbox" :checked="data.postBoxes.includes(box)">
                                                        <span v-text="item.title"></span>
                                                    </label>
                                                </li>
                                            </div>
                                            <div v-for="postType in postTypes" :class="otherGroup == postType.type ? '' : 'd-none'">
                                                <li>
                                                    <label>
                                                        <input @change="allPostType($event, postType.type)" type="checkbox">
                                                        <span>انتخاب همه</span>
                                                    </label>
                                                </li>
                                                <li v-for="(item, permType) in allPostTypesPermissions">
                                                    <label>
                                                        <input @change="postTypeModel($event, postType.type, permType)" type="checkbox" :checked="postTypeChecked.includes(postType.type + '_' + permType)">
                                                        <span v-text="item.title"></span>
                                                    </label>
                                                </li>
                                                <h3>دسته ها</h3>
                                                <li v-for="category in categories" v-if="category.post_type == postType.type">
                                                    <label>
                                                        <input @change="categoryModel($event, postType.type, category.id)" type="checkbox" :checked="categoriesChecked.includes(postType.type + '_' + category.id)">
                                                        <span v-text="category.title"></span>
                                                    </label>
                                                </li>
                                            </div>
                                            <div :class="otherGroup == 'countries' ? '' : 'd-none'">
                                                <li>
                                                    <label>
                                                        <input @change="allCountries($event)" type="checkbox">
                                                        <span>انتخاب همه</span>
                                                    </label>
                                                </li>
                                                <li v-for="item in countries">
                                                    <label>
                                                        <input v-model="data.countries" :value="item.id" type="checkbox" :checked="data.countries.includes(item.id)">
                                                        <span v-text="item.name"></span>
                                                    </label>
                                                </li>
                                            </div>
                                            <div :class="otherGroup == 'provinces' ? '' : 'd-none'">
                                                <li>
                                                    <label>
                                                        <input @change="allProvinces($event)" type="checkbox">
                                                        <span>انتخاب همه</span>
                                                    </label>
                                                </li>
                                                <li v-for="item in provinces">
                                                    <label>
                                                        <input v-model="data.provinces" :value="item.id" type="checkbox" :checked="data.provinces.includes(item.id)">
                                                        <span v-text="item.name"></span>
                                                    </label>
                                                </li>
                                            </div>
                                            <div :class="otherGroup == 'cities' ? '' : 'd-none'">
                                                <li>
                                                    <label>
                                                        <input @change="allCities($event)" type="checkbox">
                                                        <span>انتخاب همه</span>
                                                    </label>
                                                </li>
                                                <li v-for="item in cities">
                                                    <label>
                                                        <input v-model="data.cities" :value="item.id" type="checkbox" :checked="data.cities.includes(item.id)">
                                                        <span v-text="item.name"></span>
                                                    </label>
                                                </li>
                                            </div>
                                            <div :class="otherGroup == 'towns' ? '' : 'd-none'">
                                                <li>
                                                    <label>
                                                        <input @change="allTowns($event)" type="checkbox">
                                                        <span>انتخاب همه</span>
                                                    </label>
                                                </li>
                                                <li v-for="item in towns">
                                                    <label>
                                                        <input v-model="data.towns" :value="item.id" type="checkbox" :checked="data.towns.includes(item.id)">
                                                        <span v-text="item.name"></span>
                                                    </label>
                                                </li>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3 d-block d-md-none">
                            <button :disabled="disabled" class="btn btn-success w-100 py-2 px-3">{{ buttonTitle }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>

import HeadContent from '../HeadContent.vue';
import TextInputGroup from "../../forms/group/TextInputGroup";
import MultiSelectGroup from "../../forms/group/MultiSelectGroup";
import SelectGroup from "../../forms/group/SelectGroup";
import {Errors} from "../../../errors";

import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';
Vue.use(VueIziToast);

export default {
    components: {
        HeadContent,
        TextInputGroup,
        MultiSelectGroup,
        SelectGroup
    },
    data() {
        return {
            loader: true,
            pageTitle: 'ویرایش نقش',
            buttonTitle: 'ذخیره',
            disabled: false,
            buttons: [
                {
                    title: 'افزودن کاربر',
                    to: '/admin/users/create',
                    icon: 'far fa-plus',
                    type: 'secondary'
                },
                {
                    title: 'کاربران',
                    to: '/admin/users',
                    icon: 'fad fa-users',
                    type: 'secondary'
                },
                {
                    title: 'مجوز ها',
                    to: '/admin/permissions',
                    icon: 'far fa-badge-check',
                    type: 'secondary'
                },
                {
                    title: 'افزودن نقش',
                    to: '/admin/roles/create',
                    icon: 'far fa-plus',
                    type: 'secondary'
                },
                {
                    title: 'نقش ها',
                    to: '/admin/roles',
                    icon: 'fad fa-user-tie',
                    type: 'secondary'
                }
            ],
            errors: new Errors(),
            allChecked: false,
            permissionGroup: 'all',
            defaultPermissionsGroup: [],
            permissionsGroup: {},
            permissionGroupCount: {},
            roles: [],
            permissions: [],
            data: {
                towns: [],
                canSet: [],
                name: null,
                cities: [],
                label: null,
                canSetMe: [],
                postTypes: {},
                postBoxes: [],
                categories: {},
                permissions: [],
                countries: [],
                provinces: [],
                ticketDepartment: false,
            },
            otherGroup: 'postBoxes',
            postBoxes: {},
            postTypes: [],
            allPostTypesPermissions: {},
            allPostTypesPermissionsCount: 0,
            postTypeChecked: [],
            categories: [],
            categoriesChecked: [],
            countries: [],
            provinces: [],
            cities: [],
            towns: [],
        }
    },
    mounted() {
        axios.get(`/api/v1/roles/${this.$route.params.id}`)
        .then(response => {
            var role = response.data.role;
            this.data.label = role.label;
            this.data.name = role.name;
            this.data.ticketDepartment = response.data.ticketDepartment;
            this.data.canSet = response.data.canSet;
            this.data.canSetMe = response.data.canSetMe;
            this.permissions = response.data.permissions;
            this.data.permissions = response.data.rolePermissions;
            this.defaultPermissionsGroup = response.data.treeViewPermissions;
            this.setPermissionsGroup();
            this.canSet = response.data.canSet;
            this.canSetMe = response.data.canSetMe;
            this.postBoxes = response.data.postBoxes;
            this.data.postBoxes = response.data.rolePostBoxes;
            this.postTypes = response.data.postTypes;
            this.allPostTypesPermissions = response.data.allPostTypesPermissions;
            this.allPostTypesPermissionsCount = Object.keys(response.data.allPostTypesPermissions).length;
            var postTypePerm = {};
            var postTypeChecked = [];
            Object.keys(response.data.rolePostTypesPermissions).forEach(perm => {
                response.data.rolePostTypesPermissions[perm].forEach(function(type) {
                    postTypeChecked.push(type + '_' + perm);
                    if (postTypePerm[type] === undefined) {
                        postTypePerm[type] = [];
                    }
                    postTypePerm[type].push(perm);
                });
            });
            this.data.postTypes = postTypePerm;
            this.postTypeChecked = postTypeChecked;
            this.categories = response.data.categories;
            var roleCategories = this.convertArrayToInteger(response.data.roleCategories);
            var postTypeCategories = {};
            var categoriesChecked = [];
            this.categories.forEach(function (item) {
                if (postTypeCategories[item.post_type] === undefined) {
                    postTypeCategories[item.post_type] = [];
                }
                if (roleCategories.includes(item.id)) {
                    postTypeCategories[item.post_type].push(item.id);
                    categoriesChecked.push(item.post_type + '_' + item.id);
                }
            });
            this.data.categories = postTypeCategories;
            this.categoriesChecked = categoriesChecked;
            if (this.data.permissions.length == this.permissions.length) {
                this.allChecked = true;
            }
            var roles = [];
            response.data.roles.forEach(function (item, i) {
                roles[i] = {
                    id: item.id,
                    title: item.label
                };
            });
            this.roles = roles;
            this.countries = response.data.countries;
            this.provinces = response.data.provinces;
            this.cities = response.data.cities;
            this.towns = response.data.towns;
            this.data.countries = this.convertArrayToInteger(response.data.roleCountries);
            this.data.provinces = this.convertArrayToInteger(response.data.roleProvinces);
            this.data.cities = this.convertArrayToInteger(response.data.roleCities);
            this.data.towns = this.convertArrayToInteger(response.data.roleTowns);
            this.$parent.headContent({
                title: 'ویرایش ' + role.label
            });
            this.loader = false;
        })
        .catch(error => {

        });
    },
    methods: {
        onSubmit(e) {
            this.disabled = true;
            this.buttonTitle = 'لطفا صبر کنید...';
            const formData = new FormData(document.getElementById('user-form'));
            axios.put(`/api/v1/roles/${this.$route.params.id}` , this.data )
                .then(result => {
                    var response = result.data;
                    if (response.status == 'success') {
                        this.$toast.success(response.message);
                    } else {
                        this.$toast.error(response.message, 'خطا');
                    }
                    this.buttonTitle = 'ذخیره';
                    this.disabled = false;
                })
                .catch(error => {
                    this.errors.set(error.response.data.errors);
                    this.buttonTitle = 'ذخیره';
                    this.disabled = true;
                });
        },
        keydown(event) {
            var field = event.target.name;
            this.errors.clear(field);
            if (!this.errors.any()) {
                this.disabled = false;
            }
        },
        permissionsDisplay(permissionName) {
            if (this.permissionGroup == 'all')
                return false;

            if (permissionName == this.permissionGroup)
                return false

            return true;
        },
        setPermissionGroup(name) {
            this.allChecked = false;
            this.permissionGroup = name;
            if (name == 'all') {
                if (this.permissions.length == this.data.permissions.length) {
                    this.allChecked = true;
                }
            } else {
                var group = this.permissionsGroup[name];
                if(group.count == group.list.length) {
                    this.allChecked = true;
                }
            }
        },
        setPermissionsGroup() {
            var groups = {};
            var permissions = this.data.permissions;
            this.defaultPermissionsGroup.forEach(function (item, i) {
                var group = [];
                var counter = 0;
                var name = item.record.name;
                var active = permissions.includes(item.id);
                group['list'] = [];
                group['list'][0] = {
                    id: item.id,
                    title: item.title,
                    active: active
                }
                if (active) {
                    counter++;
                }
                if (item.list !== undefined) {
                    item.list.forEach(function (item2, i2) {
                        var index = i2 + 1;
                        var active = permissions.includes(item2.id);
                        group['list'][index] = {
                            id: item2.id,
                            title: item2.title,
                            active: active
                        };
                        if (active) {
                            counter++;
                        }
                    });
                }
                group['name'] = name;
                group['title'] = item.title;
                group['count'] = counter;
                groups[name] = group;
            });
            this.permissionsGroup = groups;
            this.allCount = this.data.permissio
        },
        allPermissionModel(event) {
            var check = this.allChecked;
            if (this.permissionGroup == 'all') {
                if (this.data.permissions.length > 0) {
                    this.allChecked = false;
                    this.data.permissions = [];
                } else {
                    var all = [];
                    this.permissions.forEach(function (item, i) {
                        all[i] = item.id;
                    });
                    this.allChecked = true;
                    this.data.permissions = all;
                }
            } else {
                var all = [];
                var ids = [];
                var i = 0;
                var i2 = 0;
                this.permissionsGroup[this.permissionGroup]['list'].forEach(function (item){
                    ids[i2] = item.id;
                    i2++;
                    if (event.target.checked) {
                        all[i] = item.id;
                        i++;
                    }
                });
                this.data.permissions.forEach(function (id) {
                    if(!ids.includes(id)) {
                        all[i] = id;
                        i++;
                    }
                });
                this.data.permissions = all;
            }
            this.setPermissionsGroup();
        },
        allPostBoxes(event) {
            if (event.target.checked) {
                var i = 0;
                var all = [];
                Object.keys(this.postBoxes).forEach(key => {
                    all[i] = key;
                    i++;
                });
                this.data.postBoxes = all;
            } else {
                this.data.postBoxes = [];
            }
        },
        allPostType(event, postType) {
            if (event.target.checked) {
                var i = 0;
                var i2 = this.postTypeChecked.length;
                var all = [];
                var checked = this.postTypeChecked;
                Object.keys(this.allPostTypesPermissions).forEach(key => {
                    all[i] = key;
                    i++;
                    checked[i2] = postType + '_' + key;
                    i2++;
                });
                this.data.postTypes[postType] = all;
                this.postTypeChecked = checked;
            } else {
                this.data.postTypes[postType] = [];
                var i = 0;
                var checked = [];
                this.postTypeChecked.forEach(function (key) {
                    var parts = key.split('_');
                    if (parts[0] != postType) {
                        checked[i] = key;
                        i++;
                    }
                });
                this.postTypeChecked = checked;
            }
        },
        postTypeModel(event, postType, permission) {
            if (event.target.checked) {
                this.postTypeChecked.push(postType + '_' + permission);
                var all = [];
                all.push(permission);
                if (this.data.postTypes[postType] === undefined) {
                    this.data.postTypes[postType] = [];
                }
                this.data.postTypes[postType].forEach(function (perm) {
                    all.push(perm);
                });
                this.data.postTypes[postType] = all;
            } else {
                var checked = [];
                this.postTypeChecked.forEach(function (postTypePermission) {
                    if (postTypePermission != postType + '_' + permission) {
                        checked.push(postTypePermission);
                    }
                });
                this.postTypeChecked = checked;
                var all = [];
                this.data.postTypes[postType].forEach(function (perm) {
                    if(perm != permission) {
                        all.push(perm);
                    }
                });
                this.data.postTypes[postType] = all;
            }
        },
        categoryModel(event, postType, categoryId) {
            if (event.target.checked) {
                this.categoriesChecked.push(postType + '_' + categoryId);
                var all = [];
                all.push(categoryId);
                if (this.data.categories[postType] === undefined) {
                    this.data.categories[postType] = [];
                }
                this.data.categories[postType].forEach(function (catId) {
                    all.push(catId);
                });
                this.data.categories[postType] = all;
            } else {
                var checked = [];
                this.categoriesChecked.forEach(function (postTypeCategoryId) {
                    if (postTypeCategoryId != postType + '_' + categoryId) {
                        checked.push(postTypeCategoryId);
                    }
                });
                this.categoriesChecked = checked;
                var all = [];
                this.data.categories[postType].forEach(function (catId) {
                    if(catId != categoryId) {
                        all.push(catId);
                    }
                });
                this.data.categories[postType] = all;
            }
        },
        allCountries(event) {
            if (event.target.checked) {
                var all = [];
                this.countries.forEach(function (item){
                   all.push(item.id);
                });
                this.data.countries = all;
            } else {
                this.data.countries = [];
            }
        },
        allProvinces(event) {
            if (event.target.checked) {
                var all = [];
                this.provinces.forEach(function (item){
                   all.push(item.id);
                });
                this.data.provinces = all;
            } else {
                this.data.provinces = [];
            }
        },
        allCities(event) {
            if (event.target.checked) {
                var all = [];
                this.cities.forEach(function (item){
                   all.push(item.id);
                });
                this.data.cities = all;
            } else {
                this.data.cities = [];
            }
        },
        allTowns(event) {
            if (event.target.checked) {
                var all = [];
                this.towns.forEach(function (item){
                   all.push(item.id);
                });
                this.data.towns = all;
            } else {
                this.data.towns = [];
            }
        },
        convertArrayToInteger(arr) {
            var output = [];
            arr.forEach(function (val) {
                output.push(parseInt(val));
            });
            return output;
        }
    },
    watch: {
        'data.permissions'() {
            this.setPermissionsGroup();
        }
    }
}
</script>
