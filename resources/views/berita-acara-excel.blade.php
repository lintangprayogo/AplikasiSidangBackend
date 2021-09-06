<table>
    <thead>
        <tr>
            <th style="height: 30px; width: 10px;" align="center">No</th>
            <th style="height: 30px; width: 30px;" align="center">Nama Mahasiswa</th>
            <th style="height: 30px; width: 30px;" align="center">NIM Mahasiswwa</th>
            <th style="height: 30px; width: 30px;" align="center">Jalur Sidang</th>
            <th style="height: 30px; width: 30px;" align="center">Periode Sidang</th>
            <th style="height: 30px; width: 30px;" align="center">Judul TA</th>
            
			<th style="height: 30px; width: 30px;" align="center">Kode Pembimbing 1</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 1 Pembimbing 1</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 2 Pembimbing 1</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 3 Pembimbing 1</th>
			
			<th style="height: 30px; width: 30px;" align="center">Kode Pembimbing 2</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 1 Pembimbing 2</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 2 Pembimbing 2</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 3 Pembimbing 2</th>
			
			<th style="height: 30px; width: 30px;" align="center">Kode Penguji 1</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 1 Penguji 1</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 2 Penguji 1</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 3 Penguji 1</th>

			<th style="height: 30px; width: 30px;" align="center">Kode Penguji 2</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 1 Penguji 2</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 2 Penguji 2</th>
            <th style="height: 30px; width: 30px;" align="center">CLO 3 Penguji 2</th>

			<th style="height: 30px; width: 30px;" align="center">Nilai Akhir</th>
			<th style="height: 30px; width: 30px;" align="center">Index Akhir</th>
        </tr>
    </thead>
    <tbody>
        @php $i=1 @endphp
        @foreach($sidangs as $s)
        <tr>
			<td align="center">{{ $i++ }}</td>
            <td align="center">{{$s->mhs_nama}}</td>
            <td align="center">{{$s->mhs_nim}}</td>
            <td align="center">{{$s->jalur_sidang}}</td>
            <td align="center">{{$s->periode_judul}}</td>
            <td align="center">{{$s->judul_indonesia}}</td>
           
			@if ($s->nilai_pembimbing_1)
			<td align="center">{{$s->nilai_pembimbing_1->kode_dosen}}</td>
            <td align="center">{{$s->nilai_pembimbing_1->nilai_laporan}}</td>
            <td align="center">{{$s->nilai_pembimbing_1->nilai_presentasi}}</td>
            <td align="center">{{$s->nilai_pembimbing_1->nilai_produk}}</td>
			@else
			<td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
			@endif

			@if ($s->nilai_pembimbing_2)
			<td align="center">{{$s->nilai_pembimbing_2->kode_dosen}}</td>
            <td align="center">{{$s->nilai_pembimbing_2->nilai_laporan}}</td>
            <td align="center">{{$s->nilai_pembimbing_2->nilai_presentasi}}</td>
            <td align="center">{{$s->nilai_pembimbing_2->nilai_produk}}</td>
			@else
			<td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
			@endif

			@if ($s->nilai_penguji_1)
			<td align="center">{{$s->nilai_penguji_1->kode_dosen}}</td>
            <td align="center">{{$s->nilai_penguji_1->nilai_laporan}}</td>
            <td align="center">{{$s->nilai_penguji_1->nilai_presentasi}}</td>
            <td align="center">{{$s->nilai_penguji_1->nilai_produk}}</td>
			@else
			<td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
			@endif

			@if ($s->nilai_penguji_2)
			<td align="center">{{$s->nilai_penguji_2->kode_dosen}}</td>
            <td align="center">{{$s->nilai_penguji_2->nilai_laporan}}</td>
            <td align="center">{{$s->nilai_penguji_2->nilai_presentasi}}</td>
            <td align="center">{{$s->nilai_penguji_2->nilai_produk}}</td>
			@else
			<td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
			@endif

			<td align="center">{{$s->nilai_akhir_total}}</td>
			<td align="center">{{$s->index_nilai}}</td>
        </tr>
        @endforeach
    </tbody>
</table>