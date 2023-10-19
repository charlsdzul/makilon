import { Col, Row, Table } from "antd";
import { StatusCodes } from "http-status-codes";
import QueueAnim from "rc-queue-anim";
import { default as React, useEffect, useMemo, useState } from "react";
import { useTranslation } from "react-i18next";
import CContainer from "../../Components/CContainer";
import { get } from "../../Utils/api";

const MisVacantes = (props) => {
	const { t } = useTranslation(["MisVacante"]);

	const [data, setData] = useState();
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
			// {
			// 	title: "vac_id",
			// 	dataIndex: "vac_id",
			// 	key: "vac_id",
			// 	sorter: true,
			// 	//render: (name) => `${name.first} ${name.last}`,
			// 	width: "20%",
			// },
			{
				title: "vac_titulo",
				dataIndex: "vac_titulo",
				key: "vac_titulo",
				sorter: true,

				// filters: [
				// 	{
				// 		text: "Male",
				// 		value: "male",
				// 	},
				// 	{
				// 		text: "Female",
				// 		value: "female",
				// 	},
				// ],
				width: "40%",
			},
			{
				title: "vac_created_at",
				dataIndex: "vac_created_at",
				key: "vac_created_at",
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
		<CContainer title={t("Vacante.lblAgregarVacante")}>
			<Row justify="center" style={{ marginTop: "5rem" }}>
				<Col xs={24} sm={24} md={17} lg={16} xl={14} xxl={14}>
					<QueueAnim type="scale">
						<div key="Result">
							<Table
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
							/>
						</div>
					</QueueAnim>
				</Col>
			</Row>
		</CContainer>
	);
};

export default MisVacantes;
