<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Areas</h4>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" @click="createModal = true">
                      <i class="material-icons">person</i> Add Area
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <!-- <i class="material-icons">code</i> Website
											<i class="material-icons">cloud</i> Server -->
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allAreas" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Ward</th>
                <th>City</th>
                <th>Upazila</th>
                <th>District</th>
                <th>Division</th>
                <th>Country</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allAreas.total > 0 && !tableLoading"
                  v-for="(item, index) in allAreas.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.area_name }}</td>
                  <td>{{ item.ward }}</td>
                  <td>{{ item.city }}</td>
                  <td>{{ item.upazila }}</td>
                  <td>{{ item.district }}</td>
                  <td>{{ item.division }}</td>
                  <td>{{ item.country }}</td>
                  <td>
                    <div
                      @click="openEditModal(index)"
                      :disabled="deleteLoading && deleteIndex == index"
                      class="btn btn-default"
                    >
                      Edit
                    </div>
                    <Poptip
                      confirm
                      title="Are you sure you want to delete this?"
                      @on-ok="deleteArea(index)"
                      ok-text="Delete"
                    >
                      <div
                        :class="
                          deleteLoading && deleteIndex == index
                            ? 'btn btn-primary btn-loading'
                            : 'btn btn-primary'
                        "
                      >
                        <Loader
                          v-if="deleteLoading && deleteIndex == index"
                          position="inline"
                          color="#999999"
                        />
                        <p v-else class="text-center">Delete</p>
                      </div>
                    </Poptip>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="tableLoading">
              <Loader />
            </div>
            <div
              class="d-flex justify-content-center"
              v-if="!tableLoading && allAreas.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- create modal -->
    <Modal
      title="Add Area"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent="addArea">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Name</label>
              <input
                v-model="area.area_name"
                type="text"
                class="form-control"
              />
              <p v-if="error.area_name" class="text-danger">
                *{{ error.area_name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-lg-6 col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Ward</label>
              <Select v-model="area.ward" clearable filterable>
                <Option
                  v-for="(item, index) in wards"
                  :value="item.ward_name"
                  :key="'up' + index"
                  >{{ item.ward_name }}</Option
                >
              </Select>
              <p v-if="error.ward" class="text-danger">*{{ error.ward }}</p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">City</label>
              <Select v-model="area.city" clearable filterable>
                <Option
                  v-for="(item, index) in cities"
                  :value="item.city_name"
                  :key="'up' + index"
                  >{{ item.city_name }}</Option
                >
              </Select>
              <p v-if="error.city" class="text-danger">*{{ error.city }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Upazila</label>
              <Select v-model="area.upazila" clearable filterable>
                <Option
                  v-for="(item, index) in upazilas"
                  :value="item.name"
                  :key="'up' + index"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.upazila" class="text-danger">
                *{{ error.upazila }}
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">District</label>
              <Select v-model="area.district" clearable filterable>
                <Option
                  v-for="item in districts"
                  :value="item.name"
                  :key="'ds' + item.name"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.district" class="text-danger">
                *{{ error.district }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Division</label>
              <Select v-model="area.division" clearable filterable>
                <Option
                  v-for="item in divisions"
                  :value="item.name"
                  :key="'dv' + item.name"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.division" class="text-danger">
                *{{ error.division }}
              </p>
            </div>
          </div>
        </div>
        <button
          type="submit"
          class="btn btn-primary pull-right d-flex align-items-center"
          :disabled="modal_loading"
        >
          <template v-if="modal_loading">
            <Loader position="inline" color="white" />
            <!-- <p class="text-center">Adding</p> -->
          </template>
          <template v-else>
            <p>Add</p>
          </template>
        </button>
        <button @click="closeModal" class="btn btn-default pull-right">
          <p>Cancel</p>
        </button>
        <div class="clearfix"></div>
      </form>
    </Modal>
    <!-- create modal end -->

    <!-- edit modal -->
    <Modal
      title="Edit Area"
      v-model="editModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Name</label>
              <input
                v-model="editData.area_name"
                type="text"
                class="form-control"
              />
              <p v-if="error.area_name" class="text-danger">
                *{{ error.area_name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-lg-6 col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Ward</label>
              <Select v-model="area.ward" clearable filterable>
                <Option
                  v-for="(item, index) in wards"
                  :value="item.ward_name"
                  :key="'up' + index"
                  >{{ item.ward_name }}</Option
                >
              </Select>
              <p v-if="error.ward" class="text-danger">*{{ error.ward }}</p>
            </div>
          </div>
          <div class="col-12 col-lg-6 col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">City</label>
              <Select v-model="editData.city" clearable filterable>
                <Option
                  v-for="(item, index) in cities"
                  :value="item.city_name"
                  :key="'up' + index"
                  >{{ item.city_name }}</Option
                >
              </Select>
              <p v-if="error.ward_name" class="text-danger">
                *{{ error.ward_name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Upazila</label>
              <Select v-model="editData.upazila" clearable filterable>
                <Option
                  v-for="(item, index) in upazilas"
                  :value="item.name"
                  :key="'eup' + index"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.upazila" class="text-danger">
                *{{ error.upazila }}
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">District</label>
              <Select v-model="editData.district" clearable filterable>
                <Option
                  v-for="item in districts"
                  :value="item.name"
                  :key="'eds' + item.name"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.district" class="text-danger">
                *{{ error.district }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Division</label>
              <Select v-model="editData.division" clearable filterable>
                <Option
                  v-for="item in divisions"
                  :value="item.name"
                  :key="'edv' + item.name"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.division" class="text-danger">
                *{{ error.division }}
              </p>
            </div>
          </div>
        </div>
        <button
          @click="editArea"
          class="btn btn-primary pull-right d-flex align-items-center"
          :disabled="modal_loading"
        >
          <template v-if="modal_loading">
            <Loader position="inline" color="white" />
            <!-- <p class=".text-center">Adding</p> -->
          </template>
          <template v-else>
            <p>Edit</p>
          </template>
        </button>
        <button @click="closeModal" class="btn btn-default pull-right">
          <p>Cancel</p>
        </button>
        <div class="clearfix"></div>
      </form>
    </Modal>
    <!-- edit modal end  -->
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      allAreas: [],
      tableLoading: true,
      createModal: false,
      editModal: false,
      modal_loading: false,
      area: {
        area_name: "",
        ward: "",
        city: "",
        upazila: "",
        district: "",
        division: "",
        country: "Bangladesh",
      },
      error: {
        area_name: false,
        ward: false,
        city: false,
        upazila: false,
        district: false,
        division: false,
      },
      editIndex: -1,
      deleteIndex: -1,
      deleteLoading: false,
      editData: {},
      page: 1,
    };
  },
  computed: {
    ...mapGetters({}),
  },
  methods: {
    clearData() {
      this.area = {
        area_name: "",
        ward: "",
        city: "",
        upazila: "",
        district: "",
        division: "",
        country: "Bangladesh",
      };
      this.editIndex = -1;
      this.editData = {};
    },

    clearErrorData() {
      this.error = {
        area_name: false,
        ward: false,
        city: false,
        upazila: false,
        district: false,
        division: false,
      };
    },

    openModal() {
      this.createModal = true;
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allAreas.data[index]);
      this.editIndex = _.clone(index);
    },

    closeModal() {
      this.clearErrorData();
      this.createModal = false;
      this.modal_loading = false;
      this.editIndex = -1;
      this.editData = {};
      this.editModal = false;
    },

    async editArea() {
      if (!this.validateAreaEditData()) return;
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/edit/area/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allAreas.data[this.editIndex] = _.clone(this.editData);
        this.editData = {};
        this.editIndex = -1;
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteArea(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/delete/area/${this.allAreas.data[index].id}`
      );
      if (res.status == 200) {
        this.allAreas.data.splice(index, 1);
      } else {
        this.nswr();
      }
      this.deleteIndex = -1;
      this.deleteLoading = false;
    },

    async addArea() {
      this.clearErrorData();
      if (!this.validateArea()) {
        return;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        "/app/admin/create/area/new",
        this.area
      );
      if (res.status == 201) {
        this.allAreas.data.unshift(res.data);
        this.allAreas.total += 1;
      } else if (res.status == 403) {
        if (res.data.type && res.data.type == "uid") {
          this.error.uid = res.data.message;
          this.modal_loading = false;
          return;
        }
      } else {
        this.nswr();
      }
      this.modal_loading = false;
      this.closeModal();
    },

    async paginate(page) {
      this.tableLoading = true;
      const res = await this.callApi(
        "get",
        `/app/admin/get/area/paginate?page=${this.page}`
      );
      if (res.status == 200) {
        this.allAreas = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },

    validateArea() {
      if (this.area.area_name.trim() == "") {
        this.error.area_name = "Area name is required!";
        return false;
      }
      if (this.area.ward.trim() == "") {
        this.error.ward = "Ward is required!";
        return false;
      }
      if (this.area.city.trim() == "") {
        this.error.city = "City is required!";
        return false;
      }
      if (this.area.upazila.trim() == "") {
        this.error.upazila = "Upazila is required!";
        return false;
      }
      if (this.area.district.trim() == "") {
        this.error.district = "District is required!";
        return false;
      }
      if (this.area.division.trim() == "") {
        this.error.division = "Division is required!";
        return false;
      }
      return true;
    },

    validateAreaEditData() {
      if (!this.editData.area_name || this.editData.area_name.trim() == "") {
        this.error.area_name = "Area Name is required!";
        return false;
      }
      if (!this.editData.ward || this.editData.ward.trim() == "") {
        this.error.ward = "Ward is required!";
        return false;
      }
      if (!this.editData.city || this.editData.area.trim() == "") {
        this.error.city = "City is required!";
        return false;
      }

      if (!this.editData.upazila || this.editData.upazila.trim() == "") {
        this.error.upazila = "Upazila is required!";
        return false;
      }

      if (!this.editData.district || this.editData.district.trim() == "") {
        this.error.district = "District is required!";
        return false;
      }
      if (!this.editData.division || this.editData.division.trim() == "") {
        this.error.division = "Division is required!";
        return false;
      }
      return true;
    },
  },
  async created() {
    this.paginate(1);
    const [cities, wards] = await Promise.all([
      this.callApi("get", "/app/admin/get/city/all"),
      this.callApi("get", "/app/admin/get/ward/all"),
    ]);
    if (cities.status == 200 && wards.status == 200) {
      this.$store.commit("setCities", cities.data);
      this.$store.commit("setWards", wards.data);
    } else {
      this.nswr();
    }
  },
};
</script>

<style >
.btn-loading {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>