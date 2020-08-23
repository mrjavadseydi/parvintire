<template>
    <div class="row">
        <div :class="[col]">
            <province-group v-model="provinceId" :val="provinceVal" title="استان" :name="provinceName" :error="provinceError"></province-group>
        </div>
        <div v-show="showCity" :class="[col]">
            <city-group ref="cityRef" v-model="cityId" :val="cityVal" :province-id="provinceVal" title="شهرستان" :name="cityName" :error="cityError"></city-group>
        </div>
        <div v-show="showTown" :class="[col]">
            <town-group ref="townRef" v-model="townId" :val="townVal" :city-id="cityVal" title="شهر" :name="townName" :error="townError"></town-group>
        </div>
        <div v-show="showRegion" :class="[col]">
            <region-group ref="regionRef" v-model="regionId" :val="regionVal" :town-id="townVal" title="منطقه" :name="regionName" :error="regionError"></region-group>
        </div>
    </div>
</template>
<script>
import ProvinceGroup from "./ProvinceGroup";
import CityGroup from "./CityGroup";
import TownGroup from "./TownGroup";
import RegionGroup from "./RegionGroup";
export default {
    props: [
        'col',
        'provinceVal', 'provinceName', 'provinceError',
        'cityVal', 'cityName', 'cityError',
        'townVal', 'townName', 'townError', 'withTown',
        'regionVal', 'regionName', 'regionError', 'withRegion'
    ],
    components: {
        ProvinceGroup, CityGroup, TownGroup, RegionGroup
    },
    data() {
        return {
            provinceId: '',
            cityId: '',
            townId: '',
            regionId: '',
            showCity: false,
            showTown: false,
            showRegion: false,
            provinceMount: false,
            cityMount: false,
            townMount: false
        }
    },
    watch: {
        provinceId(provinceId) {
            if (provinceId) {
                this.showCity = true;
            } else {
                this.showCity = false;
            }
            this.showTown = false;
            this.showRegion = false;
            this.$refs.cityRef.update(provinceId);
            if (this.provinceMount) {
                this.$emit('result', {
                    provinceId: this.provinceId,
                    cityId: '',
                    townId: '',
                    regionId: ''
                });
            } else {
                this.$emit('result', {
                    provinceId: this.provinceId,
                    cityId: this.cityVal,
                    townId: this.townVal,
                    regionId: this.regionVal
                });
                this.provinceMount = true;
            }
        },
        cityId(cityId) {
            this.showRegion = false;
            if (cityId) {
                if(this.withTown) {
                    this.showTown = true;
                    this.$refs.townRef.update(cityId);
                }
                if (this.cityMount) {
                    this.$emit('result', {
                        provinceId: this.provinceId,
                        cityId: this.cityId,
                        townId: '',
                        regionId: ''
                    });
                } else {
                    this.$emit('result', {
                        provinceId: this.provinceId,
                        cityId: this.cityId,
                        townId: this.townVal,
                        regionId: this.regionVal
                    });
                    this.cityMount = true;
                }
            } else {
                this.showTown = false;
            }
        },
        townId(townId) {
            if (townId) {
                if (this.withRegion) {
                    this.showRegion = true;
                    this.$refs.regionRef.update(townId);
                }
                if(this.townMount) {
                    this.$emit('result', {
                        provinceId: this.provinceId,
                        cityId: this.cityId,
                        townId: this.townId,
                        regionId: ''
                    });
                } else {
                    this.$emit('result', {
                        provinceId: this.provinceId,
                        cityId: this.cityId,
                        townId: this.townId,
                        regionId: this.regionVal
                    });
                    this.townMount = true;
                }
            } else {
                this.showRegion = false;
            }
        },
        regionId(regionId) {
            this.$emit('result', {
                provinceId: this.provinceId,
                cityId: this.cityId,
                townId: this.townId,
                regionId: this.regionId
            });
        },
        provinceVal(provinceId) {
            this.provinceId = provinceId;
        },
        cityVal(cityId) {
            this.cityId = cityId;
        },
        townVal(townId) {
            this.townId = townId;
        },
        regionVal(regionId) {
            this.regionId = regionId;
        }
    }
}
</script>
