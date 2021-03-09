<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Elections</h4>
              </div>
            </div>
          </div>
          <div v-if="allElections" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElections.total > 0 && !tableLoading"
                  v-for="(item, index) in allElections.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.date_and_time | formatDateTime }}</td>
                  <td>
                    <div
                      @click="
                        $router.push(`/alumni/elections/candidate/${item.id}`)
                      "
                      class="btn btn-primary"
                    >
                      See Candidates
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="tableLoading">
              <Loader />
            </div>
            <div
              class="d-flex justify-content-center"
              v-if="!tableLoading && allElections.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      allElections: [],
      tableLoading: true,
    };
  },

  methods: {
    async paginate(page) {
      this.tableLoading = true;
      const res = await this.callApi(
        "get",
        `/app/admin/election/paginate/all?page=${page}`
      );
      if (res.status == 200) {
        this.allElections = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },
  },
  async created() {
    this.paginate(1);
  },
};
</script>
