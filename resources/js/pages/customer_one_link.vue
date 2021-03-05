<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <!-- link1 -->
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Link 1</h4>
            <!-- <p class="card-category">Here is a subtitle for this table</p> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>No.</th>
                  <th>Name</th>
                  <th>UID</th>
                </thead>
                <tbody>
                  <tr
                    v-for="(item, index) in oneLinkData"
                    :key="'oneL' + index"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                    <td class="text-primary">
                      {{ item.customer ? item.customer.uid : "N/A" }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex justify-content-center" v-if="tableLoading">
                <Loader />
              </div>
              <div
                class="d-flex justify-content-center"
                v-if="!tableLoading && oneLinkData.length == 0"
              >
                <p>No Link Found</p>
              </div>
            </div>
          </div>
        </div>
        <!-- link1 end -->

        <!-- link 2  -->
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Link 2</h4>
            <!-- <p class="card-category">Here is a subtitle for this table</p> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>No.</th>
                  <th>Name</th>
                  <th>UID</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in link2" :key="'oneL' + index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                    <td class="text-primary">
                      {{ item.customer ? item.customer.uid : "N/A" }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex justify-content-center" v-if="tableLoading">
                <Loader />
              </div>
              <div
                class="d-flex justify-content-center"
                v-if="!tableLoading && link2.length == 0"
              >
                <p>No Link Found</p>
              </div>
            </div>
          </div>
        </div>
        <!-- link 2 end -->

        <!-- link 3  -->
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Link 3</h4>
            <!-- <p class="card-category">Here is a subtitle for this table</p> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>No.</th>
                  <th>Name</th>
                  <th>UID</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in link3" :key="'oneL' + index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                    <td class="text-primary">
                      {{ item.customer ? item.customer.uid : "N/A" }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex justify-content-center" v-if="tableLoading">
                <Loader />
              </div>
              <div
                class="d-flex justify-content-center"
                v-if="!tableLoading && link3.length == 0"
              >
                <p>No Link Found</p>
              </div>
            </div>
          </div>
        </div>
        <!-- link 3 end -->

        <!-- link 4 -->
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Link 4</h4>
            <!-- <p class="card-category">Here is a subtitle for this table</p> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>No.</th>
                  <th>Name</th>
                  <th>UID</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in link4" :key="'oneL' + index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                    <td class="text-primary">
                      {{ item.customer ? item.customer.uid : "N/A" }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex justify-content-center" v-if="tableLoading">
                <Loader />
              </div>
              <div
                class="d-flex justify-content-center"
                v-if="!tableLoading && link4.length == 0"
              >
                <p>No Link Found</p>
              </div>
            </div>
          </div>
        </div>
        <!-- link 4 end -->

        <!-- link 5  -->
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Link 5</h4>
            <!-- <p class="card-category">Here is a subtitle for this table</p> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>No.</th>
                  <th>Name</th>
                  <th>UID</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in link5" :key="'oneL' + index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                    <td class="text-primary">
                      {{ item.customer ? item.customer.uid : "N/A" }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex justify-content-center" v-if="tableLoading">
                <Loader />
              </div>
              <div
                class="d-flex justify-content-center"
                v-if="!tableLoading && link5.length == 0"
              >
                <p>No Link Found</p>
              </div>
            </div>
          </div>
        </div>
        <!-- link 5 end -->

        <!-- link 6 -->
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Link 6</h4>
            <!-- <p class="card-category">Here is a subtitle for this table</p> -->
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table">
                <thead class="text-primary">
                  <th>No.</th>
                  <th>Name</th>
                  <th>UID</th>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in link6" :key="'oneL' + index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                    <td class="text-primary">
                      {{ item.customer ? item.customer.uid : "N/A" }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex justify-content-center" v-if="tableLoading">
                <Loader />
              </div>
              <div
                class="d-flex justify-content-center"
                v-if="!tableLoading && link6.length == 0"
              >
                <p>No Link Found</p>
              </div>
            </div>
          </div>
        </div>
        <!-- link 6 end  -->
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      id: 1,
      oneLinkData: [],
      tableLoading: true,
    };
  },

  computed: {
    link2() {
      let len = Math.floor(this.oneLinkData.length / 3);
      let array = [];
      for (let i = 0; i < len; i++) {
        array.push(this.oneLinkData[i]);
      }
      return array;
    },
    link3() {
      let len = Math.floor(this.oneLinkData.length / 12);
      let array = [];
      for (let i = 0; i < len; i++) {
        array.push(this.oneLinkData[i]);
      }
      return array;
    },
    link4() {
      let len = Math.floor(this.oneLinkData.length / 60);
      let array = [];
      for (let i = 0; i < len; i++) {
        array.push(this.oneLinkData[i]);
      }
      return array;
    },
    link5() {
      let len = Math.floor(this.oneLinkData.length / 360);
      let array = [];
      for (let i = 0; i < len; i++) {
        array.push(this.oneLinkData[i]);
      }
      return array;
    },
    link6() {
      let len = Math.floor(this.oneLinkData.length / 2520);
      let array = [];
      for (let i = 0; i < len; i++) {
        array.push(this.oneLinkData[i]);
      }
      return array;
    },
  },

  async created() {
    this.tableLoading = true;
    const res = await this.callApi("get", "/app/customer/get/bic/all");
    if (res.status == 200) {
      this.oneLinkData = res.data;
    }
    this.tableLoading = false;
  },
};
</script>