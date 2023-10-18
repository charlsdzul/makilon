import { Col, Row, Table } from "antd";
import { StatusCodes } from "http-status-codes";
import { default as React, useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import CContainer from "../../Components/CContainer";
import { get } from "../../Utils/api";

const columns = [
	{
		title: "id",
		dataIndex: "id",
		sorter: true,
		//render: (name) => `${name.first} ${name.last}`,
		width: "20%",
	},
	{
		title: "titulo",
		dataIndex: "titulo",
		filters: [
			{
				text: "Male",
				value: "male",
			},
			{
				text: "Female",
				value: "female",
			},
		],
		width: "20%",
	},
	{
		title: "vac_created_at",
		dataIndex: "vac_created_at",
	},
];

const getRandomuserParams = (params) => ({
	results: params.pagination?.pageSize,
	page: params.pagination?.current,
	...params,
});

const MisVacantes = (props) => {
	const { t } = useTranslation(["MisVacante"]);

	const [data, setData] = useState();
	const [loading, setLoading] = useState(false);
	const [tableParams, setTableParams] = useState({
		pagination: {
			current: 1,
			pageSize: 10,
		},
	});

	const llenarGrid = async () => {
		setLoading(true);

		const response = await get({ url: "misvacantes", params: { page: 1, perPage: 1, total: tableParams.totalCount } });
		console.log(response);

		if (response.status === StatusCodes.OK) {
			setData(response.data.results);
			setLoading(false);

			// setTableParams({
			// 	...tableParams,
			// 	pagination: {
			// 		...tableParams.pagination,
			// 		totalCount: response.data.totalCount,
			// 		// 200 is mock data, you should read it from server
			// 		// total: data.totalCount,
			// 	},
			// });
		}

		// console.log(response);

		// setData(response);
		// setLoading(false);

		// fetch(`https://randomuser.me/api?${QueryString.stringify(getRandomuserParams(tableParams))}`)
		// 	.then((res) => res.json())
		// 	.then(({ results }) => {
		// 		console.log(results);
		// 		setData(results);
		// 		setLoading(false);
		// 		setTableParams({
		// 			...tableParams,
		// 			pagination: {
		// 				...tableParams.pagination,
		// 				total: 200,
		// 				// 200 is mock data, you should read it from server
		// 				// total: data.totalCount,
		// 			},
		// 		});
		// 	});
	};

	const iniciarPrograma = async () => {
		llenarGrid();

		// const { data: puestos } = await obtenerCatalogo({ catalogo: "puestos" });
		// setSourcePuestos(puestos);
		// const { data: puestosEspecificos } = await obtenerCatalogo({ catalogo: "puestosEspecificos" });
		// setSourcePuestosEspecificos(puestosEspecificos);
	};

	useEffect(() => {
		llenarGrid();
	}, [tableParams]);

	useEffect(() => {
		iniciarPrograma();
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	const handleTableChange = (pagination, filters, sorter) => {
		console.log(pagination);
		setTableParams({
			pagination,
			filters,
			...sorter,
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
					{/* <QueueAnim type="scale">
						<div key="Result"> */}
					<Table
						columns={columns}
						rowKey={(record) => record.id}
						dataSource={data}
						pagination={tableParams.pagination}
						loading={loading}
						onChange={handleTableChange}
					/>
					{/* </div>
					</QueueAnim> */}
				</Col>
			</Row>
		</CContainer>
	);
};

export default MisVacantes;
