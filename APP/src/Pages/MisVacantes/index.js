import { MenuOutlined } from "@ant-design/icons";
import { Col, Dropdown, Row, Space, Table } from "antd";
import { StatusCodes } from "http-status-codes";
import QueueAnim from "rc-queue-anim";
import { default as React, useEffect, useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import { Link } from "react-router-dom";
import CContainer from "../../Components/CContainer";
import { get } from "../../Utils/api";
import "./index.css";

const items = [
	{
		label: "1st menu item",
		key: "1",
	},
	{
		label: "2nd menu item",
		key: "2",
	},
	{
		label: "3rd menu item",
		key: "3",
	},
];
const MisVacantes = (props) => {
	const { t } = useTranslation(["MisVacantes"]);

	const [data, setData] = useState();
	const [visibleRowContextMenu, setVisibleRowContextMenu] = useState(false);

	const [loading, setLoading] = useState(false);
	const [tableParams, setTableParams] = useState({
		pagination: {
			current: 1,
			pageSize: 10,
		},
		sorter: {
			field: "",
			order: "",
		},
	});

	const columns = useMemo(
		() => [
			{
				title: "Action",
				dataIndex: "operation",
				key: "operation",
				fixed: "right",
				width: "4rem",
				render: () => (
					<Space size="middle">
						<Dropdown
							menu={{
								items,
							}}>
							<a>
								<MenuOutlined />
							</a>
						</Dropdown>
					</Space>
				),
			},
			{
				title: t("MisVacantes.captionTitulo"),
				dataIndex: "vac_titulo",
				key: "vac_titulo",
				sorter: true,
				width: "25rem",
				render: (text, record, index) => {
					return <Link to={`/vacante/${record.vac_id}/edit`}>{text}</Link>;
				},
			},
			{
				title: t("MisVacantes.captionPuesto"),
				dataIndex: "vac_puesto",
				key: "vac_puesto",
				sorter: true,
				width: "10rem",
			},
			{
				title: t("MisVacantes.captionPuestoOtro"),
				dataIndex: "vac_puesto_otro",
				key: "vac_puesto_otro",
				sorter: true,
				width: "10rem",
			},
			{
				title: t("MisVacantes.captionPuestoEspecifico"),
				dataIndex: "vac_puesto_especifico",
				key: "vac_puesto_especifico",
				sorter: true,
				width: "10rem",
			},
			{
				title: t("MisVacantes.captionPuestoEspecificoOtro"),
				dataIndex: "vac_puesto_especifico_otro",
				key: "vac_puesto_especifico_otro",
				sorter: true,
				width: "10rem",
			},
			{
				title: t("MisVacantes.captionCreado"),
				dataIndex: "vac_created_at",
				key: "vac_created_at",
				sorter: true,
				width: "10rem",
			},
		],
		// eslint-disable-next-line react-hooks/exhaustive-deps
		[]
	);

	const llenarGrid = async () => {
		setLoading(true);
		//console.log(tableParams);

		const response = await get({
			url: "misvacantes",
			params: {
				pagination: {
					page: tableParams.pagination.current,
					perPage: tableParams.pagination.pageSize,
				},
				sorter: {
					field: tableParams.sorter.field,
					order: tableParams.sorter.order,
				},
			},
		});

		if (response.status === StatusCodes.OK) {
			setData(response.data.results);
			setLoading(false);
			setTableParams({
				...tableParams,
				pagination: {
					...tableParams.pagination,
					total: response.data.totalCount,
				},
			});
		}
	};

	const iniciarPrograma = async () => {
		llenarGrid();
	};

	useEffect(() => {
		llenarGrid();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [JSON.stringify(tableParams)]);

	useEffect(() => {
		iniciarPrograma();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	const handleTableChange = (pagination, filters, sorter) => {
		console.log(pagination);
		console.log(filters);
		console.log(sorter);
		setTableParams({
			pagination,
			filters,
			sorter: {
				field: sorter?.field ?? "",
				order: sorter?.order ?? "",
			},
		});

		// `dataSource` is useless since `pageSize` changed
		if (pagination.pageSize !== tableParams.pagination?.pageSize) {
			setData([]);
		}
	};

	return (
		<CContainer title={t("MisVacantes.lblMisVacantes")}>
			<Row justify="center" style={{ marginTop: "5rem" }}>
				<Col xs={24} sm={24} md={24} lg={24} xl={24} xxl={24}>
					<QueueAnim type="scale">
						<div key="Result">
							<Table
								scroll={{ x: 1500, y: "25rem" }}
								size="small"
								sticky
								columns={columns}
								rowKey={(record) => record.vac_id}
								dataSource={data}
								pagination={tableParams.pagination}
								loading={loading}
								onChange={handleTableChange}
								summary={() => {
									return (
										<Table.Summary.Row>
											<Table.Summary.Cell index={0}>Total de registros: {tableParams.pagination.total}</Table.Summary.Cell>
										</Table.Summary.Row>
									);
								}}
								onRow={(record, rowIndex) => {
									return {
										onClick: (event) => {}, // click row
										onDoubleClick: (event) => {}, // double click row
									};
								}}
							/>
						</div>
					</QueueAnim>
				</Col>
			</Row>
		</CContainer>
	);
};

export default MisVacantes;
