<?php

namespace Thotam\ThotamTeam\Jobs;

use Illuminate\Bus\Queueable;
use Thotam\ThotamTeam\Models\Nhom;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\DB;

class Nhom_Sync_Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $nhom, $mnv, $teams;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Nhom $nhom, $mnv = null, $teams = null)
    {
        $this->nhom = $nhom;
        $this->mnv = $mnv;
        $this->teams = $teams;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::connection('member')->table('teams')->updateOrInsert(
            [
                'id' => $this->nhom->id,
            ],
            [
                'name' => $this->nhom->full_name,
                'order' => $this->nhom->order,
                'active' => $this->nhom->active,
            ]
        );

        DB::connection('member')->table('teams_have_leaders')->where('team_id', $this->nhom->id)->delete();

        DB::connection('member')->table('teams_have_members')->where('team_id', $this->nhom->id)->delete();

        if (!!$this->mnv && !!$this->teams) {
            DB::connection('member')->table('teams_have_members')->where('member_mnv', $this->mnv)->whereNotIn('team_id', $this->teams)->delete();
        }

        $leaders = $this->nhom->nhom_has_quanlys;

        foreach ($leaders as $leader) {
            DB::connection('member')->table('teams_have_leaders')->insert([
                'leader_mnv' => $leader->key,
                'team_id' => $this->nhom->id
            ]);
        }

        $members = $this->nhom->nhom_has_thanhviens;

        foreach ($members as $member) {
            DB::connection('member')->table('teams_have_members')->insert([
                'member_mnv' => $member->key,
                'team_id' => $this->nhom->id
            ]);
        }

    }
}
