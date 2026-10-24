# Register Deliverable Proyek Santika

Register ini membedakan artefak yang sudah disiapkan dari aktivitas yang masih perlu dilakukan oleh tim atau stakeholder. Status dokumen tidak berarti UAT atau persetujuan stakeholder telah terjadi.

| No. | Deliverable workflow | Artefak/status | Tindak lanjut |
|---:|---|---|---|
| 1 | Project Name | Santika; dicatat di `README.md` | Pastikan nama final disetujui tim |
| 2 | BRD | [BRD_Santika.md](BRD_Santika.md) — draft siap ditinjau | Stakeholder tinjau tujuan, lingkup, profil, Admin menyewa/tidak, dan denda |
| 3 | Flow Chart / ERD | [flowchart-erd.md](flowchart-erd.md) — diagram Mermaid | Tinjau alur dan skema; ekspor gambar bila format submission meminta PNG/PDF |
| 4 | PRD | [PRD_Santika.md](PRD_Santika.md) — draft siap ditinjau | Stakeholder menyetujui acceptance criteria dan lingkup |
| 5 | Design, F&B | [design-spec.md](design-spec.md) merangkum desain aktual; F&B belum didefinisikan | Konfirmasi arti F&B dan artefak wajibnya; jangan menandai langkah ini selesai sebelum ada keputusan |
| 6 | UAT Results | [UAT.md](UAT.md) berisi 20 skenario dan hasil test otomatis | UAT manual oleh Admin/anggota/stakeholder belum dilakukan; isi hasil aktual dan persetujuan |
| 7 | User Manual | [manual-book.md](manual-book.md) tersedia dan memuat aturan denda terkini | Review manual setelah UAT dan perbarui jika alur UI berubah |
| 8 | Project Documentation | `README.md`, [technical-documentation.md](technical-documentation.md), [requirement-traceability.md](requirement-traceability.md), serta dokumen di direktori `docs/` | Lengkapi diagram export/screenshot dan pastikan BRD/SRS/PRD versi final konsisten |
| 9 | Push proyek ke GitLab | Belum diverifikasi/dilakukan sebagai bagian pekerjaan ini | Tim perlu meninjau perubahan, commit sesuai proses tim, lalu push seluruh source dan dokumen ke GitLab |

## Checklist penutupan

- [ ] Stakeholder menyetujui BRD dan PRD.
- [ ] FR-04 diselaraskan: profil wajib untuk anggota, bukan Admin.
- [ ] Stakeholder menyetujui denda Rp50.000 per hari dan aturan tanggal kalender.
- [ ] Arti “F&B” dan artefak yang diwajibkan sudah diklarifikasi.
- [ ] UAT browser selesai; status/catatan aktual terisi pada setiap skenario.
- [ ] Temuan UAT memiliki keputusan (diperbaiki, diterima, atau ditunda) dan blocker selesai.
- [ ] Manual pengguna dan dokumentasi teknis cocok dengan hasil UAT.
- [ ] Screenshot/diagram final ditambahkan bila diminta format penyerahan.
- [ ] Tim meninjau, commit, dan push hasil final ke GitLab.
