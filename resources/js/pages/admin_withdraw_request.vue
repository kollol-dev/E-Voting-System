<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">All Withdraws</h4>
                <ul
                  v-if="allWithdrawHistoy.length == 0"
                  class="nav nav-tabs"
                  data-tabs="tabs"
                ></ul>
              </div>
            </div>
          </div>
          <div class="card-body">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>NO.</th>
                <th>ID.</th>
                <th>Date</th>
                <th>Name</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allWithdrawHistoy.length > 0 && !cardLoading"
                  v-for="(item, index) in allWithdrawHistoy"
                  :key="'wd' + index"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.id }}</td>
                  <td>{{ item.created_at | formatDateTime }}</td>
                  <td>{{ item.customer ? item.customer.name : "N/A" }}</td>
                  <td>{{ item.amount }}</td>
                  <td
                    :style="`color: ${
                      item.status == 'approved'
                        ? 'green'
                        : item.status == 'rejected'
                        ? 'red'
                        : 'black'
                    };text-transform: capitalize;`"
                  >
                    {{ item.status }}
                  </td>
                  <td>
                    <template v-if="item.status == 'pending'">
                      <Poptip
                        confirm
                        title="Are you sure you want to approve this request?"
                        @on-ok="changeWithdrawStatus('approved', index)"
                        ok-text="Approve"
                        :disabled="
                          btnLoading == 'approved' && loadingIndex == index
                        "
                      >
                        <div
                          :class="
                            btnLoading == 'approved' && loadingIndex == index
                              ? 'btn btn-success btn-loading'
                              : 'btn btn-success'
                          "
                        >
                          <Loader
                            v-if="
                              btnLoading == 'approved' && loadingIndex == index
                            "
                            position="inline"
                            color="#fff"
                          />
                          <p v-else>Approve</p>
                        </div>
                      </Poptip>
                      <Poptip
                        confirm
                        title="Are you sure you want to reject this request?"
                        @on-ok="changeWithdrawStatus('rejected', index)"
                        ok-text="Reject"
                        :disabled="
                          btnLoading == 'approved' && loadingIndex == index
                        "
                      >
                        <div
                          :class="
                            btnLoading == 'rejected' && loadingIndex == index
                              ? 'btn btn-primary btn-loading'
                              : 'btn btn-primary'
                          "
                        >
                          <Loader
                            v-if="
                              btnLoading == 'rejected' && loadingIndex == index
                            "
                            position="inline"
                            color="#999999"
                          />
                          <p v-else class="text-center">Reject</p>
                        </div>
                      </Poptip>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="cardLoading">
              <Loader />
            </div>
            <div
              class="d-flex justify-content-center"
              v-if="!cardLoading && allWithdrawHistoy.length == 0"
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
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      allWithdrawHistoy: [],
      cardLoading: true,
      btnLoading: false,
      loadingIndex: -1,
    };
  },
  methods: {
    async changeWithdrawStatus(status, index) {
      this.btnLoading = status;
      this.loadingIndex = index;
      const res = await this.callApi(
        "post",
        "/app/admin/withdraw/status/update",
        { status: status, id: this.allWithdrawHistoy[index].id }
      );
      if (res.status == 200) {
        this.ns("Withdraw Requset Added!");
        this.allWithdrawHistoy[index].status = status;
      } else {
        this.swr();
      }
      this.btnLoading = false;
      this.loadingIndex = -1;
    },
  },
  async created() {
    this.cardLoading = true;
    const customers = await this.callApi(
      "get",
      "/app/admin/get/reqeust/withdraw/all"
    );
    if (customers.status == 200) {
      this.allWithdrawHistoy = customers.data;
    } else {
      this.nswr();
    }
    this.cardLoading = false;
  },
};
</script>