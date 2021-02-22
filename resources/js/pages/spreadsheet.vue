<template>
  <div>
    <Breadcrumbs title="Spreadsheet"/>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div class="card-title">
                <h5>Spreadsheet</h5><span>To note down things quickly</span>

              </div>
              <div class="card-buttons">
                <button class="btn btn-primary" @click="download">Download CSV</button>
                <button class="btn btn-primary" @click="save">Save</button>
              </div>
            </div>
            <div class="card-body">
<!--              <hot-table :settings="settings" ref="spreadsheet"></hot-table>-->

            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
</template>

<script>
  // import { HotTable } from '@handsontable/vue';
    export default {
        name: "spreadsheet",
      middleware:['auth'],
      data: function() {
        return {
          settings: {
            licenseKey:"non-commercial-and-evaluation",
            data: [
              [],
            ],
            width: '100%',
            height: window.innerHeight*0.7,
            minRows:100,
            minCols:50,
            colHeaders: true,
            rowHeaders: true,
            manualColumnResize: true,
            manualRowResize: true,
            manualColumnMove: true,
            manualRowMove: true,
            manualColumnFreeze: true,
            manualRowFreeze: true,
            contextMenu: true,
            bindRowsWithHeaders: true,
            collapsibleColumns: true,
            columnSorting : true,
            fillHandle : true,
            mergeCells : true,

          }
        };
      },
      components: {
        // HotTable
      },
      created() {
        this.$store.dispatch('spreadsheet/fetchSheet');
      },
      methods:{
        download(){
          const exportFile = this.$refs.spreadsheet.hotInstance.getPlugin('exportFile');
          exportFile.downloadFile('csv', {
            bom: false,
            columnDelimiter: ',',
            columnHeaders: false,
            exportHiddenColumns: true,
            exportHiddenRows: true,
            fileExtension: 'csv',
            filename: 'Spreadsheet_[YYYY]-[MM]-[DD]',
            mimeType: 'text/csv',
            rowDelimiter: '\r\n',
            rowHeaders: true
          });
        },
        save(){
          const data = this.$refs.spreadsheet.hotInstance.getSourceData();
          console.log(data);
        }
      }
    }
</script>

<!--<style src="handsontable/dist/handsontable.full.css"></style>-->
<style>
  .page-wrapper .page-main-header{
    z-index: 1500;
  }
</style>
