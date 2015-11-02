<?php
namespace Models;

use Dhtmlx\Connector;
use Models\Event as Event;
use Resources;

class Dhtmlx
{
    public function __construct()
    {
        Resources\Import::composer();
        $this->db = new Resources\Database;
        $this->conn = new Connector\JSONDataConnector($this->db, "MySQLi");
    }

    public function test()
    {

        $this->conn->dynamic_loading(30);

        $this->conn->render_sql("
			SELECT
			b.id_reg_pd,
			b.nipd,
			b.nm_pd,
			b.nm_lemb,
			b.mulai_smt,
			a.id_smt,
			c.nm_stat_mhs,
			a.ips,
			a.ipk,
			a.sks_smt,
			a.sks_total
			FROM kuliah_mahasiswa a
			INNER JOIN (
				SELECT
				aa.id_reg_pd, aa.nipd, bb.nm_pd, cc.nm_lemb, aa.mulai_smt
				FROM mahasiswa_pt aa
				INNER JOIN mahasiswa bb ON aa.id_pd = bb.id_pd
				INNER JOIN sms cc ON aa.id_sms = cc.id_sms
			) b ON a.id_reg_pd = b.id_reg_pd
			INNER JOIN status_mahasiswa c ON a.id_stat_mhs = c.id_stat_mhs

			WHERE b.nipd = '8211014'
			ORDER BY a.id_smt DESC
		", "id_reg_pd", "nm_pd");

        // $this->conn->render_table("mahasiswa","id_pd","nm_pd,jk,tmpt_lahir,tgl_lahir");
    }

    public function model()
    {
        $this->conn->configure("test_events", "event_id", "start_date, end_date, event_name");
        $this->conn->useModel(new Event);
        $this->conn->dynamic_loading(30);
        //bisa menggunakan render_sql
        $this->conn->render();
    }
}
