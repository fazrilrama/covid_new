<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="generator" content="PhpSpreadsheet, https://github.com/PHPOffice/PhpSpreadsheet">
      <meta name="author" content="DFC" />
      <meta name="company" content="Hewlett-Packard Company" />
    <style type="text/css">
      html { font-family:Calibri, Arial, Helvetica, sans-serif; font-size:11pt; background-color:white }
      a.comment-indicator:hover + div.comment { background:#ffd; position:absolute; display:block; border:1px solid black; padding:0.5em }
      a.comment-indicator { background:red; display:inline-block; border:1px solid black; width:0.5em; height:0.5em }
      div.comment { display:none }
      table { border-collapse:collapse; page-break-after:always }
      .gridlines td { border:1px dotted black }
      .gridlines th { border:1px dotted black }
      .b { text-align:center }
      .e { text-align:center }
      .f { text-align:right }
      .inlineStr { text-align:left }
      .n { text-align:right }
      .s { text-align:left }
     
      td.style1 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style1 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style2 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style2 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style3 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:3px double #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style3 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:3px double #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style4 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style4 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style5 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style5 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style6 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      th.style6 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      td.style7 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      th.style7 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      td.style8 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      th.style8 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      td.style9 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style9 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style10 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      th.style10 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      td.style11 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style11 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style12 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
      th.style12 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
      td.style13 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
      th.style13 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
      td.style14 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style14 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style15 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style15 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style16 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style16 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style17 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style17 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style18 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style18 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style19 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style19 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style20 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style20 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style21 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:9pt; background-color:white }
      th.style21 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:9pt; background-color:white }
      td.style22 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:10pt; background-color:white }
      th.style22 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:10pt; background-color:white }
      td.style23 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style23 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style24 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style24 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style25 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style25 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style26 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:3px double #000000 ; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
      th.style26 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:3px double #000000 ; border-right:none #000000; color:#000000; font-family:'Calibri'; font-size:11pt; background-color:white }
      td.style27 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style27 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style28 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:9pt; background-color:white }
      th.style28 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:9pt; background-color:white }
      td.style29 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style29 { vertical-align:bottom; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style30 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style30 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style31 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style31 { vertical-align:bottom; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style32 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      th.style32 { vertical-align:bottom; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      td.style33 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      th.style33 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      td.style34 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      th.style34 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      td.style35 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      th.style35 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      td.style36 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style36 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style37 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      th.style37 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      td.style38 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style38 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style39 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style39 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style40 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style40 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style41 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      th.style41 { vertical-align:middle; text-align:left; padding-left:0px; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:12pt; background-color:white }
      td.style42 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style42 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style43 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style43 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style44 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style44 { vertical-align:middle; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style45 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style45 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style46 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style46 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style47 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style47 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style48 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style48 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style49 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style49 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style50 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style50 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style51 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style51 { vertical-align:bottom; text-align:left; padding-left:0px; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style52 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style52 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style53 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style53 { vertical-align:bottom; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style54 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style54 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style55 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style55 { vertical-align:bottom; text-align:center; border-bottom:3px double #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style56 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style56 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style57 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style57 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style58 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style58 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style59 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style59 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style60 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      th.style60 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      td.style61 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      th.style61 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:8pt; background-color:white }
      td.style62 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style62 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style63 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style63 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style64 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style64 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style65 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style65 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style66 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style66 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style67 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style67 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style68 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style68 { vertical-align:bottom; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style69 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; text-decoration:underline; color:#000000; font-family:'Tahoma'; font-size:20pt; background-color:white }
      th.style69 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:none #000000; font-weight:bold; text-decoration:underline; color:#000000; font-family:'Tahoma'; font-size:20pt; background-color:white }
      td.style70 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style70 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style71 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style71 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style72 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style72 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style73 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style73 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style74 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style74 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style75 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style75 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style76 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style76 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style77 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style77 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style78 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style78 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:3px double #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style79 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style79 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style80 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style80 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style81 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style81 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style82 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style82 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:1px solid #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style83 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style83 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style84 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style84 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style85 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style85 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:none #000000; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style86 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style86 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:3px double #000000 ; border-left:none #000000; border-right:1px solid #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style87 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style87 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:3px double #000000 ; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style88 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style88 { vertical-align:middle; text-align:center; border-bottom:none #000000; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      td.style89 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      th.style89 { vertical-align:middle; text-align:center; border-bottom:1px solid #000000 ; border-top:none #000000; border-left:1px solid #000000 ; border-right:3px double #000000 ; font-weight:bold; color:#000000; font-family:'Tahoma'; font-size:11pt; background-color:white }
      table.sheet0 col.col0 { width:21.85555519pt }
      table.sheet0 col.col1 { width:18.46666634pt }
      table.sheet0 col.col2 { width:76.07777679pt }
      table.sheet0 col.col3 { width:12.87777763pt }
      table.sheet0 col.col4 { width:111.32222083pt }
      table.sheet0 col.col5 { width:50.32222153pt }
      table.sheet0 col.col6 { width:50.32222153pt }
      table.sheet0 col.col7 { width:2.87777763pt }
      table.sheet0 col.col8 { width:50.32222153pt }
      table.sheet0 col.col9 { width:50.32222153pt }
      table.sheet0 col.col10 { width:50.32222153pt }
      table.sheet0 col.col11 { width:50.32222153pt }
      table.sheet0 col.col12 { width:36.08888836pt }
      table.sheet0 col.col13 { width:50.32222153pt }
      table.sheet0 col.col14 { width:50.32222153pt }
      table.sheet0 col.col15 { width:53.71111038pt }
      table.sheet0 col.col16 { width:50.32222153pt }
      table.sheet0 col.col17 { width:50.32222153pt }
      table.sheet0 col.col18 { width:59.13333254pt }
      table.sheet0 col.col19 { width:61.84444362pt }
      table.sheet0 col.col20 { width:53.03333261pt }
      table.sheet0 col.col21 { width:2pt }
      table.sheet0 tr { height:15pt }
      table.sheet0 tr.row2 { height:24.6pt }
      table.sheet0 tr.row4 { height:15.6pt }
      table.sheet0 tr.row5 { height:15.6pt }
      table.sheet0 tr.row6 { height:15.6pt }
      table.sheet0 tr.row7 { height:15.6pt }
      table.sheet0 tr.row8 { height:15.6pt }
      table.sheet0 tr.row9 { height:15.6pt }
      table.sheet0 tr.row10 { height:15.6pt }
      table.sheet0 tr.row11 { height:15.6pt }
      table.sheet0 tr.row12 { height:15.6pt }
      table.sheet0 tr.row13 { height:15.6pt }
      table.sheet0 tr.row14 { height:15.6pt }
      table.sheet0 tr.row15 { height:15.6pt }
      table.sheet0 tr.row16 { height:15.6pt }
      table.sheet0 tr.row17 { height:16.2pt }
      table.sheet0 tr.row18 { height:15pt }
      table.sheet0 tr.row19 { height:15pt }
      table.sheet0 tr.row20 { height:45.75pt }
      table.sheet0 tr.row22 { height:15pt }
      table.sheet0 tr.row23 { height:24.9pt }
      table.sheet0 tr.row24 { height:24.9pt }
      table.sheet0 tr.row25 { height:24.9pt }
      table.sheet0 tr.row26 { height:24.9pt }
      table.sheet0 tr.row27 { height:24.9pt }
      table.sheet0 tr.row28 { height:24.9pt }
      table.sheet0 tr.row29 { height:15pt }
    </style>
	<style>
	@page  { margin-left: 0in; margin-right: 0in; margin-top: 0.25in; margin-bottom: 0.25in; }
	body { margin-left: 0in; margin-right: 0in; margin-top: 0.25in; margin-bottom: 0.25in; }
	</style>
  </head>

  <body>

    <table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines">
        <col class="col0">
        <col class="col1">
        <col class="col2">
        <col class="col3">
        <col class="col4">
        <col class="col5">
        <col class="col6">
        <col class="col7">
        <col class="col8">
        <col class="col9">
        <col class="col10">
        <col class="col11">
        <col class="col12">
        <col class="col13">
        <col class="col14">
        <col class="col15">
        <col class="col16">
        <col class="col17">
        <col class="col18">
        <col class="col19">
        <col class="col20">
        <col class="col21">
        <tbody>
          <tr class="row0">
            <td class="column0">&nbsp;</td>
            <td class="column1"><img src="<?php echo e(public_path().'/logo.png'); ?>" alt="Logo BGR" width="100" height="20"></td>
            <td class="column2"></td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7">&nbsp;</td>
            <td class="column8">&nbsp;</td>
            <td class="column9">&nbsp;</td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row1">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7">&nbsp;</td>
            <td class="column8">&nbsp;</td>
            <td class="column9">&nbsp;</td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row2">
            <td class="column0">&nbsp;</td>
            <td class="column1 style69 s style69" colspan="20">BERITA ACARA STOCK OPNAME</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row3">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7">&nbsp;</td>
            <td class="column8">&nbsp;</td>
            <td class="column9">&nbsp;</td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row4">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Telah dilakukan perhitungan persediaan (stock) pupuk pada</td>
            <td class="column3 style33 null"></td>
            <td class="column4 style33 null"></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row5">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 null"></td>
            <td class="column3 style33 null"></td>
            <td class="column4 style33 null"></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row6">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Hari</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style37 s"><?php echo e(\Carbon\Carbon::parse($stock_opname->date)->format('D')); ?></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9">&nbsp;</td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row7">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Tanggal</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style41 s"><?php echo e(\Carbon\Carbon::parse($stock_opname->date)->format('d M Y')); ?></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row8">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Waktu</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style37 s"><?php echo e(\Carbon\Carbon::parse($stock_opname->date)->format('H:m')); ?></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row9">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Nama Gudang</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style37 s"><?php echo e($stock_opname->warehouse->name); ?></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row10">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Alamat</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style37 s"><?php echo e($stock_opname->warehouse->address); ?></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row11">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Kapasitas</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style37 s"><?php echo e(number_format($stock_opname->warehouse->length * $stock_opname->warehouse->width, 2, ',', '.')); ?> Ton</td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row12">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 null"></td>
            <td class="column3 style33 null"></td>
            <td class="column4 style33 null"></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row13">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style32 s">Dihitung Oleh</td>
            <td class="column3 style33 s">:</td>
            <td class="column4 style33 null"></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style32 null"></td>
            <td class="column8 style1 null"></td>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
		  <?php $__currentLoopData = json_decode($stock_opname->calculated_by); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <tr class="row14">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2 style34 n"><?php echo e($loop->iteration); ?></td>
            <td class="column3 style33 null"></td>
            <td class="column4 style37 s"><?php echo e($value); ?></td>
            <td class="column5 style32 null"></td>
            <td class="column6 style32 null"></td>
            <td class="column7 style35 s">:</td>
			<?php if($loop->index == 0): ?>
            <td class="column8 style1 s">Spv. Warehouse</td>
			<?php elseif($loop->index == 1): ?>
            <td class="column8 style1 s">Lead Warehouse</td>
			<?php elseif($loop->index == 2): ?>
            <td class="column8 style1 s">Checker</td>
			<?php else: ?>
            <td class="column8 style1 s"></td>
			<?php endif; ?>
            <td class="column9 style1 null"></td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
		  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <tr class="row18">
            <td class="column0">&nbsp;</td>
            <td class="column1 style76 s style78" rowspan="3">No</td>
            <td class="column2 style70 s style75" colspan="2" rowspan="3">Nama Gudang</td>
            <td class="column4 style64 s style66" rowspan="3">Jenis Barang</td>
            <td class="column4 style64 s style66" rowspan="3">Storage</td>
            <td class="column5 style84 s style86" colspan="5">Stock Awal Tanggal <?php echo e(\Carbon\Carbon::parse($stock_opname->date)->format('d M Y')); ?></td>
            <td class="column10 style83 s style83" colspan="5">Realisasi s/d Tanggal Pemeriksaan</td>
            <td class="column15 style83 s style83" colspan="5">Stock Adm./Phisik Setelah Rekonsiliasi Tanggal <?php echo e(\Carbon\Carbon::parse($stock_opname->date)->format('d M Y')); ?></td>
            <td class="column20 style87 s style89" rowspan="3">Selisih</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row19">
            <td class="column0">&nbsp;</td>
            <td class="column5 style79 s style66" rowspan="2">PO/STO</td>
            <td class="column6 style79 s style66" rowspan="2">SO</td>
            <td class="column7 style56 s style59" colspan="2" rowspan="2">Stock Menurut Kartu Adm.</td>
            <td class="column9 style80 s style81" rowspan="2">Stock Menurut Kartu Phisik</td>
            <td class="column10 style82 s style82" colspan="3">PENERIMAAN</td>
            <td class="column13 style82 s style82" colspan="2">PENGELUARAN</td>
            <td class="column15 style80 s style81" rowspan="2">Stock Adm.</td>
            <td class="column16 style82 s style82" colspan="2">Belum Realisasi</td>
            <td class="column18 style79 s style66" rowspan="2">Stock Phisik</td>
            <td class="column19 style79 s style66" rowspan="2">Stock Taking</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row20">
            <td class="column0">&nbsp;</td>
            <td class="column10 style5 s">Adm</td>
            <td class="column11 style5 s">Phisik</td>
            <td class="column12 style22 s">Susut BAR</td>
            <td class="column13 style5 s">Adm.</td>
            <td class="column14 style5 s">Phisik</td>
            <td class="column16 style5 s">PO/STO</td>
            <td class="column17 style5 s">SO</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row21">
            <td class="column0">&nbsp;</td>
            <td class="column1 style6 null"></td>
            <td class="column2 style60 n style61" colspan="2">1</td>
            <td class="column4 style10 n">2</td>
            <td class="column4 style10 n"></td>
            <td class="column5 style7 n">3</td>
            <td class="column6 style7 n">4</td>
            <td class="column7 style60 n style61" colspan="2">5</td>
            <td class="column9 style7 n">6</td>
            <td class="column10 style7 n">7</td>
            <td class="column11 style7 n">8</td>
            <td class="column12 style7 n">9</td>
            <td class="column13 style7 n">10</td>
            <td class="column14 style7 n">11</td>
            <td class="column15 style7 n">12</td>
            <td class="column16 style7 n">13</td>
            <td class="column17 style7 n">14</td>
            <td class="column18 style7 n">15</td>
            <td class="column19 style7 n">16</td>
            <td class="column20 style8 n">17</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row22">
            <td class="column0">&nbsp;</td>
            <td class="column1 style3 null"></td>
            <td class="column1 style3 null"></td>
            <td class="column2 style54 null style55" colspan="2"></td>
            <td class="column4 style11 null"></td>
            <td class="column5 style2 null"></td>
            <td class="column6 style2 null"></td>
            <td class="column7 style54 null style55" colspan="2"></td>
            <td class="column9 style21 s">(5-3)+4</td>
            <td class="column10 style2 null"></td>
            <td class="column11 style2 null"></td>
            <td class="column12 style2 null"></td>
            <td class="column13 style2 null"></td>
            <td class="column14 style2 null"></td>
            <td class="column15 style21 s">(5+7+9)-10</td>
            <td class="column16 style21 s">(3+7)-8-9)</td>
            <td class="column17 style21 s">(4+10)-11</td>
            <td class="column18 style21 s">(6+8)-11</td>
            <td class="column19 style2 null"></td>
            <td class="column20 style28 s">(16-15)</td>
            <td class="column21">&nbsp;</td>
          </tr>
		  <?php
				$total_sto_awal = 0;
				$total_so_awal = 0;
				$total_stock_awal_adm = 0;
				$total_phisik_awal = 0;
				$total_realisasi_adm_penerimaan = 0;
				$total_realisasi_phisik_penerimaan = 0;
				$total_susut_bar = 0;
				$total_realisasi_adm_pengeluaran = 0;
				$total_realisasi_phisik_pengeluaran = 0;
				$total_stock_akhir_adm = 0;
				$total_po_sto_akhir = 0;
				$total_so_akhir = 0;
				$total_stock_phisik_akhir = 0;
				$total_stock_taking_akhir = 0;
				$total_selisih = 0;
			?>
			<?php $__currentLoopData = $stock_opname->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<?php 
			$total_sto_awal+= $detail->sto_awal;
			$total_so_awal += $detail->so_awal;
			$total_stock_awal_adm += $detail->stock_awal_adm;
			$total_phisik_awal += $detail->phisik_awal;
			$total_realisasi_adm_penerimaan += $detail->realisasi_adm_penerimaan;
			$total_realisasi_phisik_penerimaan += $detail->realisasi_phisik_penerimaan;
			$total_susut_bar += $detail->susut_bar;
			$total_realisasi_adm_pengeluaran += $detail->realisasi_adm_pengeluaran;
			$total_realisasi_phisik_pengeluaran += $detail->realisasi_phisik_pengeluaran;
			$total_stock_akhir_adm += $detail->stock_akhir_adm;
			$total_po_sto_akhir += $detail->po_sto_akhir;
			$total_so_akhir += $detail->so_akhir;
			$total_stock_phisik_akhir += $detail->stock_phisik_akhir;
			$total_stock_taking_akhir += $detail->stock_taking_akhir;
			$total_selisih += $detail->selisih;
			?>
          <tr class="row23">
            <td class="column0">&nbsp;</td>
            <td class="column0"><?php echo e($loop->iteration); ?></td>
            <td class="column2 style67 s style68" colspan="2"><?php echo e($stock_opname->warehouse->name); ?></td>
            <td class="column4 style38 s"><?php echo e($detail->item ?  $detail->item->name : ''); ?></td>
            <td class="column4 style38 s"><?php echo e($detail->item ?  $detail->item->name : ''); ?></td>
            <td class="column5 style20 n"><?php echo e($detail->sto_awal); ?></td>
            <td class="column6 style20 n">&nbsp;&nbsp;<?php echo e($detail->so_awal); ?> </td>
            <td class="column7 style62 n style63" colspan="2">&nbsp;&nbsp;<?php echo e($detail->stock_awal_adm); ?> </td>
            <td class="column9 style20 f">&nbsp;&nbsp;<?php echo e($detail->phisik_awal); ?> </td>
            <td class="column10 style20 n"><?php echo e($detail->realisasi_adm_penerimaan); ?></td>
            <td class="column11 style20 n"><?php echo e($detail->realisasi_phisik_penerimaan); ?></td>
            <td class="column12 style20 n"><?php echo e($detail->susut_bar); ?></td>
            <td class="column13 style20 n"><?php echo e($detail->realisasi_adm_pengeluaran); ?></td>
            <td class="column14 style20 n"><?php echo e($detail->realisasi_phisik_pengeluaran); ?></td>
            <td class="column15 style20 f">&nbsp;&nbsp;<?php echo e($detail->stock_akhir_adm); ?></td>
            <td class="column16 style20 f"><?php echo e($detail->po_sto_akhir); ?></td>
            <td class="column17 style20 f">&nbsp;&nbsp;<?php echo e($detail->so_akhir); ?> </td>
            <td class="column18 style20 f">&nbsp;&nbsp;<?php echo e($detail->stock_phisik_akhir); ?> </td>
            <td class="column19 style20 n">&nbsp;&nbsp;<?php echo e($detail->stock_taking_akhir); ?> </td>
            <td class="column20 style29 f"><?php echo e($detail->selisih); ?></td>
            <td class="column21 style26 null"></td>
          </tr>
		  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
         
          <tr class="row28">
            <td class="column0">&nbsp;</td>
            <td class="column1 style4 null"></td>
            <td class="column2 style42 s style44" colspan="4">TOTAL</td>
            <td class="column5 style25 f"><?php echo e($total_sto_awal); ?></td>
            <td class="column6 style25 f">&nbsp;&nbsp;<?php echo e($total_so_awal); ?> </td>
            <td class="column7 style47 f style48" colspan="2">&nbsp;&nbsp;<?php echo e($total_stock_awal_adm); ?> </td>
            <td class="column9 style25 f">&nbsp;&nbsp;<?php echo e($total_phisik_awal); ?> </td>
            <td class="column10 style25 f"><?php echo e($total_realisasi_adm_penerimaan); ?></td>
            <td class="column11 style25 f"><?php echo e($total_realisasi_phisik_penerimaan); ?></td>
            <td class="column12 style25 f"><?php echo e($total_susut_bar); ?></td>
            <td class="column13 style25 f"><?php echo e($total_realisasi_adm_pengeluaran); ?></td>
            <td class="column14 style25 f"><?php echo e($total_realisasi_phisik_pengeluaran); ?></td>
            <td class="column15 style27 f">&nbsp;&nbsp;<?php echo e($total_stock_akhir_adm); ?> </td>
            <td class="column16 style27 f"><?php echo e($total_po_sto_akhir); ?></td>
            <td class="column17 style25 f">&nbsp;&nbsp;<?php echo e($total_so_akhir); ?></td>
            <td class="column18 style27 f">&nbsp;&nbsp;<?php echo e($total_stock_phisik_akhir); ?> </td>
            <td class="column19 style25 f">&nbsp;&nbsp;<?php echo e($total_stock_taking_akhir); ?> </td>
            <td class="column20 style31 f"><?php echo e($total_selisih); ?></td>
            <td class="column21 style26 null"></td>
          </tr>
          <tr class="row29">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7">&nbsp;</td>
            <td class="column8">&nbsp;</td>
            <td class="column9">&nbsp;</td>
            <td class="column10">&nbsp;</td>
            <td class="column11">&nbsp;</td>
            <td class="column12">&nbsp;</td>
            <td class="column13">&nbsp;</td>
            <td class="column14">&nbsp;</td>
            <td class="column15">&nbsp;</td>
            <td class="column16">&nbsp;</td>
            <td class="column17">&nbsp;</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row30">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style49 s style51" colspan="10">Membenarkan atas hasil perhitungan tersebut di atas :</td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row31">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style14 null"></td>
            <td class="column9 style15 null"></td>
            <td class="column10 style14 null"></td>
            <td class="column11 style15 null"></td>
            <td class="column12 style14 null"></td>
            <td class="column13 style15 null"></td>
            <td class="column14 style14 null"></td>
            <td class="column15 style15 null"></td>
            <td class="column16 style14 null"></td>
            <td class="column17 style15 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row32">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style16 null"></td>
            <td class="column9 style17 null"></td>
            <td class="column10 style16 null"></td>
            <td class="column11 style17 null"></td>
            <td class="column12 style16 null"></td>
            <td class="column13 style17 null"></td>
            <td class="column14 style16 null"></td>
            <td class="column15 style17 null"></td>
            <td class="column16 style16 null"></td>
            <td class="column17 style17 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row33">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style16 null"></td>
            <td class="column9 style17 null"></td>
            <td class="column10 style16 null"></td>
            <td class="column11 style17 null"></td>
            <td class="column12 style16 null"></td>
            <td class="column13 style17 null"></td>
            <td class="column14 style16 null"></td>
            <td class="column15 style17 null"></td>
            <td class="column16 style16 null"></td>
            <td class="column17 style17 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row34">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style16 null"></td>
            <td class="column9 style17 null"></td>
            <td class="column10 style16 null"></td>
            <td class="column11 style17 null"></td>
            <td class="column12 style16 null"></td>
            <td class="column13 style17 null"></td>
            <td class="column14 style16 null"></td>
            <td class="column15 style17 null"></td>
            <td class="column16 style16 null"></td>
            <td class="column17 style17 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row35">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style18 null"></td>
            <td class="column9 style19 null"></td>
            <td class="column10 style18 null"></td>
            <td class="column11 style19 null"></td>
            <td class="column12 style18 null"></td>
            <td class="column13 style17 null"></td>
            <td class="column14 style18 null"></td>
            <td class="column15 style19 null"></td>
            <td class="column16 style18 null"></td>
            <td class="column17 style19 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row36">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style45 s style46" colspan="2"></td>
            <td class="column10 style45 null style46" colspan="2"></td>
            <td class="column12 style45 s style46" colspan="2"></td>
            <td class="column14 style45 s style46" colspan="2"></td>
            <td class="column16 style16 null"></td>
            <td class="column17 style17 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
          <tr class="row37">
            <td class="column0">&nbsp;</td>
            <td class="column1">&nbsp;</td>
            <td class="column2">&nbsp;</td>
            <td class="column3">&nbsp;</td>
            <td class="column4">&nbsp;</td>
            <td class="column5">&nbsp;</td>
            <td class="column6">&nbsp;</td>
            <td class="column7 style13 null"></td>
            <td class="column8 style45 s style46" colspan="2">Lead  Warehouse</td>
            <td class="column10 style45 null style46" colspan="2"></td>
            <td class="column12 style45 s style46" colspan="2">Spv.Warehouse</td>
            <td class="column14 style45 s style46" colspan="2">Senior Manager</td>
            <td class="column16 style14 null"></td>
            <td class="column17 style15 null"></td>
            <td class="column18">&nbsp;</td>
            <td class="column19">&nbsp;</td>
            <td class="column20">&nbsp;</td>
            <td class="column21">&nbsp;</td>
          </tr>
        </tbody>
    </table>
  </body>
</html>
