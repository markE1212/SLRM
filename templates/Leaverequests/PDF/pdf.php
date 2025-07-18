<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page{
            margin: 0px 0px 0px 0px !important;
            padding: 0px 0px 0px 0px !important;
        }
        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
        }
        .capital{
            text-transform: uppercase;
        }
        .content{
            margin: 0px 80px 80px 80px;
            text-align: justify;
        }
    </style>
</head>
<body>
<?php echo $this->Html->image('top.png',['width'=>'100%','fullBase'=>true]); ?>
<div class="content">
    <br/><br/>
<div class="table-responsive mt-4 mb-3">
    <table width="100%">
        <tr>
            <td width="60%"></td>
            <td>Surat Kami</td>
            <td>:</td>
            <td>UiTM/100(<?php echo $leaverequest->request_id; ?>)</td>
        </tr>
        <tr>
            <td></td>
            <td>Tarikh</td>
            <td>:</td>
            <td><?php echo date('d F Y', strtotime($leaverequest->leave_date)) ?></td>
        </tr>
    </table>
</div>
<br/><br/>
<?= h($leaverequest->student->name); ?><br/>
Pensyarah, Fakulti Sains Maklumat,<br/>
UiTM Cawangan Selangor Kampus Puncak Perdana,<br/>
Jalan Pulau Indah Au10/A, Puncak Perdana, <br/>
40150 Shah Alam, <br/>
<b>Selangor</b>

<br/><br/>
<b class="capital">Penghargaan ATAS KHIDMAT MENGAWAS PEPERIKSAAN SEMESTER</b>
<br/><br/>

Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae vero odio doloribus praesentium corrupti minus non ad ratione corporis impedit ut earum expedita, aut quia commodi neque fuga architecto hic? Lorem, ipsum dolor sit amet consectetur adipisicing elit. Iure quaerat nobis fugit beatae, rem optio placeat incidunt ab quia ut asperiores omnis rerum id sequi harum voluptatum? Voluptas, rerum nemo?

<br/><br/>

2. Lorem ipsum dolor, sit amet consectetur adipisicing elit. Inventore vel commodi, deleniti harum quos ullam ea et adipisci at exercitationem hic, blanditiis tempora dicta laudantium repellendus ipsam, a temporibus repudiandae? Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque soluta quibusdam ipsa quisquam reprehenderit error veniam iusto voluptatem ea, molestiae nam dolorem natus ipsum pariatur dolor corrupti rerum, obcaecati totam?

<br/><br/>

3. Lorem ipsum dolor sit amet consectetur, adipisicing elit. Perspiciatis inventore provident sint et ratione, fuga dicta vitae similique non magni quia blanditiis asperiores exercitationem suscipit aperiam natus unde rerum autem. Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt provident perspiciatis non cumque in vitae ducimus ea nulla ratione reiciendis. Voluptates, unde debitis! Dignissimos vero, iste labore facilis porro natus?

<br/><br/>
Sekian, terima kasih
<br/><br/>
<b>(Nama Dekan)</b><br/>
Fakulti Sains Maklumat<br/>
UiTM Cawangan Selangor Kampus Puncak Perdana
<br/><br/>
SURAT DIJANA OLEH KOMPUTER. TIADA TANDATANGAN DIPERLUKAN.
<br/><br/>
    </div>
    <br/><br/><br/><br/><br/><br/><br/><br/>
<?php echo $this->Html->image('bottom.png',['width'=>'100%','fullBase'=>true]); ?>
</body>
</html>